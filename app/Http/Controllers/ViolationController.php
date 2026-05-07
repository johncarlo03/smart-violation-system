<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'offense_id' => 'required|exists:offenses,id',
            'description' => 'required|string',
            'evidence_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 1. Get the Student to find their Department ID
        $student = \App\Models\User::findOrFail($request->student_id);

        // 2. Handle the Image Upload
        $imagePath = null;
        if ($request->hasFile('evidence_image')) {
            $imagePath = $request->file('evidence_image')->store('evidence', 'public');
        }

        // 3. Save the Violation
        \App\Models\Violation::create([
            'student_id' => $student->id,
            'cso_id' => auth()->id(),
            'department_id' => $student->department_id,
            'offense_id' => $request->offense_id,
            'description' => $request->description,
            'evidence_image' => $imagePath,
        ]);

        return back()->with('status', 'Violation has been recorded successfully.');
    }
}
