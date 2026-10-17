<?php

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        $all = Violation::with(['offense', 'penalty', 'appeals'])
            ->where('student_id', $studentId)
            ->where('status', '!=', 'void')
            ->orderBy('created_at', 'asc')
            ->get();

        // Offense number: only resolved violations count toward the history
        $resolvedCounts = [];

        foreach ($all as $violation) {
            if ($violation->status === 'dismissed') {
                $violation->offense_number = null;
                continue;
            }

            $id = $violation->offense_id;
            $resolvedCounts[$id] ??= 0;

            if ($violation->status === 'resolved') {
                $resolvedCounts[$id]++;
                $violation->offense_number = $resolvedCounts[$id];
            } else { // pending: it would be the next one
                $violation->offense_number = $resolvedCounts[$id] + 1;
                $violation->recommended_penalty = $violation->offense?->penalties
                ->firstWhere('level', min($violation->offense_number, 3));
            }
        }

        $pendingViolations = $all->where('status', 'pending')->sortByDesc('created_at')->values();
        $resolvedViolations = $all->where('status', 'resolved')->sortByDesc('created_at')->values();
        $dismissedViolations = $all->where('status', 'dismissed')->sortByDesc('created_at')->values();

        $totalViolationsCount = $all->count();
        $pendingViolationsCount = $pendingViolations->count();

        return view('student.dashboard', compact(
            'pendingViolations',
            'resolvedViolations',
            'dismissedViolations',
            'totalViolationsCount',
            'pendingViolationsCount'
        ));
    }
    public function search(Request $request)
    {
        $q = $request->query('q');

        return User::where('role', 1)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('id_number', 'like', "%{$q}%")
                    ->orWhere('rfid_number', 'like', "%{$q}%");
            })
            // This is what powers your "Repeat Offender" card
            ->withCount([
                'violations' => function ($query) {
                    $query->where('status', '!=', 'dismissed');
                }
            ])
            ->limit(10)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'id_number' => $student->id_number,
                    'course_name' => $student->course->name,
                    'rfid' => $student->rfid_number,
                    'year_level' => $student->year_level,
                    'department_id' => $student->department_id,
                    'profile_photo_url' => $student->profile_photo_url,
                    'violation_count' => $student->violations_count, // Sent to JS
                    'dept_name' => $student->department->acronym ?? 'N/A',
                    'badge_color' => $student->department->badge_color ?? 'bg-gray-100 text-gray-700 border-gray-200',
                ];
            });
    }
}
