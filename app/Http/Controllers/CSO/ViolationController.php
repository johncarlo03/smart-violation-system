<?php

namespace App\Http\Controllers\CSO;

use App\Events\ViolationUpdated;
use App\Notifications\ViolationAssigned;
use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Models\User;
use \App\Models\Offense;

class ViolationController extends Controller
{

    private function getUserOffice(): string
    {
        return match ((int) auth()->user()->role) {
            7 => 'dean',
            6 => 'chairman',
            3 => 'sao',
            default => abort(403, 'Unauthorized access.'),
        };
    }

    private function getPendingStatus(): string
    {
        return match ($this->getUserOffice()) {
            'chairman' => 'pending_chairman',
            'dean' => 'pending_dean',
            'sao' => 'pending_sao',
        };
    }


    public function index()
    {
        $office = $this->getUserOffice();
        $pendingStatus = $this->getPendingStatus();

        $user = auth()->user();
        $departmentId = $user->department_id;

        $filterByDepartment = in_array($office, ['dean', 'chairman'], true);

        if ($filterByDepartment && !$departmentId) {
            abort(403, 'Your account has no assigned department.');
        }

        // Dean and Chairman must have an assigned department.
        $filterByDepartment = in_array($office, ['dean', 'chairman'], true);

        $totalViolations = Violation::query()
            ->when(
                $filterByDepartment,
                fn($query) => $query->where('department_id', $departmentId)
            )
            ->where(function ($query) use ($pendingStatus, $office) {
                $query->where('status', $pendingStatus)
                    ->orWhere(function ($query) use ($office) {
                        $query->whereIn('status', ['dismissed', 'resolved'])
                            ->where('resolved_by', $office);
                    });
            })
            ->count();

        $pendingReview = Violation::query()
            ->when(
                $filterByDepartment,
                fn($query) => $query->where('department_id', $departmentId)
            )
            ->where('status', $pendingStatus)
            ->count();

        $majorIncidents = Violation::query()
            ->when(
                $filterByDepartment,
                fn($query) => $query->where('department_id', $departmentId)
            )
            ->whereHas('offense', function ($query) {
                $query->whereIn('category', ['Serious', 'Very Serious']);
            })
            ->where('status', $pendingStatus)
            ->count();

        return view('sao.violations', compact(
            'totalViolations',
            'pendingReview',
            'majorIncidents'
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
            ->whereIn('status', ['pending_chairman', 'pending_sao', 'pending_dean', 'resolved'])
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

        // $previousViolations = Violation::where('student_id', $student->id)
        //     ->where('offense_id', $offense->id)
        //     ->whereIn('status', ['pending_chairman', 'pending_sao', 'pending_dean', 'resolved'])
        //     ->count();

        // $offenseNumber = $previousViolations + 1;
        // $penaltyLevel = min($offenseNumber, 3);

        // $penalty = $offense->penalties()
        //     ->where('level', $penaltyLevel)
        //     ->first();


        $user = auth()->user();

        $status = match ((int) $user->role) {
            2, 3, 7 => 'pending_sao',
            5 => 'pending_chairman',
            6 => 'pending_dean',


            default => throw new \Exception('Unauthorized role to report a violation.'),
        };

        $violation = Violation::create([
            'student_id' => $student->id,
            'cso_id' => auth()->id(),
            'department_id' => $student->department_id,
            'offense_id' => $request->offense_id,
            // 'penalty_id' => $penalty?->id,
            'description' => $request->description,
            'evidence_image' => $imagePath,
            'status' => $status,
        ]);

        $office = $this->officeForStatus($status);

        $this->notifyOffice($office, $violation, 'reported');

        // Broadcast real-time notification
        ViolationUpdated::dispatch(
            $violation,
            $office,
            'reported'
        );

        return back()->with(
            'status',
            'Violation has been recorded successfully.'
        );
    }

    public function forward(Violation $violation)
    {
        $user = auth()->user();

        if (
            $user->role == 6 &&
            $violation->status === 'pending_chairman'
        ) {
            $violation->update([
                'status' => 'pending_dean',
            ]);

            $this->notifyOffice('dean', $violation, 'forwarded');

            ViolationUpdated::dispatch(
                $violation,
                'dean',
                'forwarded'
            );


            return back()->with(
                'success',
                'Violation successfully forwarded to the Dean.'
            );
        }

        // Dean: pending_dean → pending_sao
        if (
            $user->role == 7 &&
            $violation->status === 'pending_dean'
        ) {
            $violation->update([
                'status' => 'pending_sao',
            ]);

            $this->notifyOffice('sao', $violation, 'forwarded');

            ViolationUpdated::dispatch(
                $violation,
                'sao',
                'forwarded'
            );

            return back()->with(
                'success',
                'Violation successfully forwarded to SAO.'
            );
        }

        abort(403, 'You are not authorized to forward this violation.');
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
        $office = $this->getUserOffice();

        // if ($violation->status !== 'pending_sao') {
        //     return back()->withErrors([
        //         'violation' => 'This violation is not currently awaiting SAO review.',
        //     ]);
        // }
        $expectedStatus = $this->getPendingStatus();

        if ($violation->status !== $expectedStatus) {
            return back()->withErrors([
                'violation' => 'This violation is not currently assigned to your office.',
            ]);
        }

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
            'resolved_by' => $office,
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

        ViolationUpdated::dispatch($violation, $office, 'updated');

        return back()->with(
            'success',
            'Violation resolved successfully as ' . Number::ordinal($offenseNumber) . ' offense.'
        );
    }


    public function dismiss(Request $request, Violation $violation)
    {
        $office = $this->getUserOffice();
        $expectedStatus = $this->getPendingStatus();

        if ($violation->status !== $expectedStatus) {
            return back()->withErrors([
                'violation' => 'This violation has already been processed.',
            ]);
        }

        $validated = $request->validate([
            'dismissal_reason' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        $violation->update([
            'status' => 'dismissed',
            'resolved_by' => $office,
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

        ViolationUpdated::dispatch($violation, $office, 'updated');

        return back()->with('success', 'Violation has been dismissed.');
    }


    private function officeForStatus(string $status): string
    {
        return match ($status) {
            'pending_chairman' => 'chairman',
            'pending_dean' => 'dean',
            'pending_sao' => 'sao',
            default => throw new \InvalidArgumentException(
                "Unknown violation status: {$status}"
            ),
        };
    }

    private function notifyOffice(
        string $office,
        Violation $violation,
        string $action
    ): void {
        $role = match ($office) {
            'dean' => 7,
            'chairman' => 6,
            'sao' => 3,
            default => null,
        };

        if ($role === null) {
            return;
        }
        $users = User::where('role', $role);

        // Chairman and Dean only receive notifications
        // for violations belonging to their department.
        if (in_array($office, ['chairman', 'dean'])) {
            $users->where('department_id', $violation->department_id);
        }



        $users->get()->each(function ($user) use ($violation, $action) {
            $user->notify(
                new ViolationAssigned($violation, $action)
            );
        });
    }

}