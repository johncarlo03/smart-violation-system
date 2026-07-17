<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\OffensePenalty;
use Illuminate\Http\Request;
use App\Models\Offense;

class OffensesCreationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $offenses = Offense::with('penalties') // <-- This pulls the penalties efficiently
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            })
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('superadmin.offenses', compact('offenses', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:offenses,name',
            'category' => 'required|in:Academic,Non-Academic,Serious,Very Serious',
        ]);

        $offense = Offense::create($validated);

        foreach ($request->penalties as $level => $penalty) {
            $offense->penalties()->create([
                'level' => $level,
                'penalty_description' => $penalty['description'],
            ]);
        }
        return redirect()->route('superadmin.offenses.index')->with('success', 'Rule created successfully!');
    }

    public function destroy(Offense $offense)
    {
        $offense->delete();

        return redirect()->back()->with('success', 'Violation deleted from system records.');
    }

    public function update(Request $request, $id)
    {
        $Violation = Offense::findOrFail($id);

        $Violation->name = $request->name;
        $Violation->category = $request->category;
        $Violation->save();

        if ($request->has('penalties')) {
            foreach ($request->penalties as $penaltyId => $data) {
                $penalty = OffensePenalty::find($penaltyId);

                if ($penalty) {
                    $penalty->penalty_description = $data['description'];
                    $penalty->save();
                }
            }
        }

        

        return redirect()->route('superadmin.offenses.index')
            ->with('success', 'Violation updated successfully');
    }
}
