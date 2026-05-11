<?php

namespace App\Http\Controllers\SAO;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // 1. Top 5 Most Frequent Offenses
        $topOffenses = Violation::select('offense_id', DB::raw('count(*) as total'))
            ->with('offense:id,name') // Only pull the name for speed
            ->where('status', '!=', 'void')
            ->groupBy('offense_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // 2. Violation Count by Department
        // This joins Users and Violations to see which department is "noisiest"
        $deptStats = DB::table('violations')
            ->join('users', 'violations.student_id', '=', 'users.id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->where('violations.status', '!=', 'void')
            ->groupBy('departments.id', 'departments.name')
            ->get();

        // 3. Monthly Trend (Last 6 Months)
        $monthlyTrend = Violation::select(
                DB::raw('DATE_FORMAT(created_at, "%b") as month'),
                DB::raw('count(*) as total')
            )
            ->where('status', '!=', 'void')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('sao-dashboard', compact('topOffenses', 'deptStats', 'monthlyTrend'));
    }
}
