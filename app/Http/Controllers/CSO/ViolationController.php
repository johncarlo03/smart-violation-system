<?php

namespace App\Http\Controllers\CSO;

use App\Events\ViolationUpdated;
use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Models\User;
use \App\Models\Offense;

class ViolationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalViolations = Violation::when($user->role != 3, function ($query) {
            return $query->whereDate('created_at', today());
        })->count();

        $pendingReview = Violation::where('status', 'pending')
            ->when($user->role != 3, function ($query) {
                return $query->whereDate('created_at', today());
            })
            ->count();

        $majorIncidents = Violation::whereHas('offense', function ($query) {
            $query->whereIn('category', ['Serious', 'Very Serious']);
        })
            ->when($user->role != 3, function ($query) {
                return $query->whereDate('created_at', today());
            })
            ->count();

        $violations = Violation::with([
            'student',
            'cso',
            'department',
            'offense',
            'penalty',
            'appeals'
        ])
            ->latest()
            ->when($user->role != 3, function ($query) {
                return $query->whereDate('created_at', today());
            })
            ->paginate(10);

        return view('sao.violations', compact(
            'totalViolations',
            'pendingReview',
            'majorIncidents',
            'violations'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'offense_id' => 'required|exists:offenses,id',
            'description' => 'required|string',
            'evidence_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $student = User::findOrFail($request->student_id);
        $offense = Offense::findOrFail($request->offense_id);

        $alreadyReported = Violation::where('student_id', $student->id)
            ->where('offense_id', $request->offense_id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'resolved'])
            ->exists();

        if ($alreadyReported) {
            return back()
                ->withInput()
                ->withErrors([
                    'offense_id' => "{$student->name} has already been reported for this offense today.",
                ]);
        }
        
        $imagePath = null;

        if ($request->hasFile('evidence_image')) {
            $imagePath = $request
                ->file('evidence_image')
                ->store('evidence', 'public');
        }

        $previousViolations = Violation::where('student_id', $student->id)
            ->where('offense_id', $offense->id)
            ->whereIn('status', ['pending', 'resolved'])
            ->count();

        $offenseNumber = $previousViolations + 1;
        $penaltyLevel = min($offenseNumber, 3);

        $penalty = $offense->penalties()
            ->where('level', $penaltyLevel)
            ->first();

        Violation::create([
            'student_id' => $student->id,
            'cso_id' => auth()->id(),
            'department_id' => $student->department_id,
            'offense_id' => $request->offense_id,
            // 'penalty_id' => $penalty?->id,
            'description' => $request->description,
            'evidence_image' => $imagePath,
            'status' => 'pending',
        ]);

        ViolationUpdated::dispatch();
        return back()->with(
            'status',
            'Violation has been recorded successfully.'
        );
    }


    public function manage(Violation $violation)
    {
        $violation->load([
            'student',
            'offense.penalties',
            'department',
            'cso',
            'penalty',
            'appeals.student',
            'appeals.reviewer'
        ]);

        $previousViolations = Violation::where('student_id', $violation->student_id)
            ->where('offense_id', $violation->offense_id)
            ->where('id', '!=', $violation->id)
            ->where('status', 'resolved')
            ->count();

        $offenseNumber = $previousViolations + 1;

        $penaltyLevel = min($offenseNumber, 3);

        $recommendedPenalty = $violation->offense
            ->penalties()
            ->where('level', $penaltyLevel)
            ->first();

        $latestAppeal = $violation->appeals
            ->sortByDesc('created_at')
            ->first();

        return view('sao.manage', compact(
            'violation',
            'offenseNumber',
            'recommendedPenalty',
            'latestAppeal'
        ));
    }


    public function resolve(Request $request, Violation $violation)
    {

        $validated = $request->validate([
            'resolution_notes' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        $previousViolations = Violation::where('student_id', $violation->student_id)
            ->where('offense_id', $violation->offense_id)
            ->where('id', '!=', $violation->id)
            ->where('status', 'resolved')
            ->count();

        $offenseNumber = $previousViolations + 1;

        $penaltyLevel = min($offenseNumber, 3);

        $penalty = $violation->offense
            ->penalties()
            ->where('level', $penaltyLevel)
            ->first();

        if (!$penalty) {
            return back()
                ->withInput()
                ->withErrors([
                    'penalty' => "No penalty has been configured for {$penaltyLevel} offense."
                ]);
        } // can be removed

        $violation->update([
            'status' => 'resolved',
            'penalty_id' => $penalty->id,
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_at' => now(),
        ]);

        $violation->appeals()
            ->where('status', 'pending')
            ->update([
                'status' => 'denied',
                'sao_response' => $validated['resolution_notes'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

        ViolationUpdated::dispatch();

        return back()->with(
            'success',
            'Violation resolved successfully as ' . Number::ordinal($offenseNumber) . ' offense.'
        );
    }


    public function dismiss(Request $request, Violation $violation)
    {
        $validated = $request->validate([
            'dismissal_reason' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        if ($violation->status !== 'pending') {
            return back()->withErrors([
                'violation' => 'This violation has already been processed.',
            ]);
        }

        $violation->update([
            'status' => 'dismissed',
            'penalty_id' => null,
            'resolution_notes' => $validated['dismissal_reason'],
            'resolved_at' => now(),
        ]);

        $violation->appeals()
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'sao_response' => $validated['dismissal_reason'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

        ViolationUpdated::dispatch();

        return back()->with('success', 'Violation has been dismissed.');
    }
}