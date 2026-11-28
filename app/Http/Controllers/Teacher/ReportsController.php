<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,resolved,dismissed',
            'q' => 'nullable|string|max:100',
        ]);

        // Only violations filed by the logged-in user
        $query = Violation::where('cso_id', auth()->id())
            ->where('status', '!=', 'void');

        $pendingStatuses = [
            'pending_chairman',
            'pending_dean',
            'pending_sao',
        ];

        // Count violations by status category
        $counts = [
            'pending' => (clone $query)
                ->whereIn('status', $pendingStatuses)
                ->count(),

            'resolved' => (clone $query)
                ->where('status', 'resolved')
                ->count(),

            'dismissed' => (clone $query)
                ->where('status', 'dismissed')
                ->count(),
        ];

        $search = $request->q;

        $violations = (clone $query)
            ->with(['student', 'offense', 'penalty'])
            ->when($request->filled('status'), function ($q) use ($request, $pendingStatuses) {
                if ($request->status === 'pending') {
                    $q->whereIn('status', $pendingStatuses);
                } else {
                    $q->where('status', $request->status);
                }
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('student', function ($s) use ($search) {
                    $s->where('name', 'ilike', "%{$search}%")
                        ->orWhere('id_number', 'ilike', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('teacher.reports', [
            'violations' => $violations,
            'counts' => $counts,
            'total' => array_sum($counts),
        ]);
    }

}
