<?php

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
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
