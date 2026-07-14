<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offense;

class OffensesCreationController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $offenses = Offense::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        })
        ->orderBy('category', 'asc')
        ->orderBy('name', 'asc')
        ->get();

    return view('superadmin.offenses', compact('offenses', 'search'));
}
    
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Academic,Non-Academic,Serious,Very Serious',
        ]);
        Offense::create($validated);
        return redirect()->route('superadmin.offenses')->with('success', 'Rule created successfully!');
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

        return redirect()->route('superadmin.offenses.index')
            ->with('success', 'Violation updated successfully');
    }
}
