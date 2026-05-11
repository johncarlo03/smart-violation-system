<?php

namespace App\Http\Controllers\SAO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class SAOAdminController extends Controller
{
    public function index()
    {
        $topOffenses = Violation::select('offense_id', DB::raw('count(*) as total'))
            ->with('offense:id,name') // Only pull the name for speed
            ->where('status', '!=', 'void')
            ->groupBy('offense_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $deptStats = Department::select('id', 'name')
    ->withCount(['users as total' => function ($query) {
        $query->join('violations', 'users.id', '=', 'violations.student_id')
              ->where('violations.status', '!=', 'void');
    }]) ->get();

        // $monthlyTrend = Violation::select(
        //     DB::raw('DATE_FORMAT(created_at, "%b") as month'),
        //     DB::raw('count(*) as total')
        // )
        //     ->where('status','pending')
        //     ->where('created_at', '>=', now()->subMonths(6))
        //     ->groupBy('month')
        //     ->orderBy('created_at', 'asc')
        //     ->get();

        $totalViolations = Violation::count();
        $pendingReview = Violation::where('status', 'pending')->count();
        $majorIncidents = Violation::whereHas('offense', function ($query) {
            $query->whereIn('category', ['Serious', 'Very Serious']);
        })
            ->count();

        // 2. Get the main queue with Relationships (Eager Loading)
        $violations = Violation::with(['student', 'cso', 'department', 'offense'])
            ->latest()
            ->paginate(10);

        return view('sao-dashboard', compact('totalViolations', 'pendingReview', 'majorIncidents', 'violations', 'topOffenses', 'deptStats'));
    }
}
