<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'status' => 'nullable|in:pending,resolved,dismissed',
            'q'      => 'nullable|string|max:100',
        ]);
 
        // Only violations filed by the logged-in user
        $base = Violation::where('cso_id', auth()->id())
            ->where('status', '!=', 'void');
 
        $counts = (clone $base)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
 
        $search = $request->q;
 
        $violations = (clone $base)
            ->with(['student', 'offense', 'penalty'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($search, fn ($q) => $q->whereHas('student', fn ($s) => $s
                ->where('name', 'like', "%{$search}%")
                ->orWhere('id_number', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();
 
        return view('teacher.reports', [
            'violations' => $violations,
            'counts'     => $counts,
            'total'      => $counts->sum(),
        ]);
    }
}
