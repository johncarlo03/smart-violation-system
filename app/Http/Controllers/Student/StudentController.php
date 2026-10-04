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

    // Get violations in chronological order first
    $studentViolations = Violation::with(['offense', 'penalty', 'appeals'])
        ->where('student_id', $studentId)
        ->where('status', '!=', 'void')
        ->orderBy('created_at', 'asc')
        ->get();

    // Calculate offense number based on chronological history
    $offenseCounts = [];

    foreach ($studentViolations as $violation) {
        $offenseId = $violation->offense_id;

        if (!isset($offenseCounts[$offenseId])) {
            $offenseCounts[$offenseId] = 0;
        }

        $offenseCounts[$offenseId]++;

        $violation->offense_number = $offenseCounts[$offenseId];
    }

    $studentViolations = $studentViolations
        ->sortBy(function ($violation) {
            return $violation->status === 'pending' ? 0 : 1;
        })
        ->values();

    $totalViolationsCount = $studentViolations->count();

    $pendingViolationsCount = $studentViolations
        ->where('status', 'pending')
        ->count();

    return view('student.dashboard', compact(
        'studentViolations',
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
                    $query->where('status', '!=', 'void');
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
                    'violation_count' => $student->violations_count, // Sent to JS
                    'dept_name' => $student->department->acronym ?? 'N/A',
                    'badge_color' => $student->department->badge_color ?? 'bg-gray-100 text-gray-700 border-gray-200',
                ];
            });
    }
}
