<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Violation;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    public function store(Request $request, Violation $violation)
    {
        // Make sure this violation belongs to the logged-in student
        if ($violation->student_id !== auth()->id()) {
            abort(403);
        }

        // Only resolved violations can be appealed
        if ($violation->status !== 'pending') {
            return back()->with('error', 'Only pending violations can be appealed.');
        }

        // Don't allow another pending appeal
        $existingAppeal = Appeal::where('violation_id', $violation->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingAppeal) {
            return back()->with(
                'error',
                'You already have a pending appeal for this violation.'
            );
        }

        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'min:20',
                'max:2000',
            ],

            'evidence_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        $evidencePath = null;

        if ($request->hasFile('evidence_image')) {
            $evidencePath = $request
                ->file('evidence_image')
                ->store('appeals', 'public');
        }

        Appeal::create([
            'violation_id' => $violation->id,
            'student_id' => auth()->id(),
            'reason' => $validated['reason'],
            'evidence_image' => $evidencePath,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Your appeal has been submitted successfully.'
        );
    }
}