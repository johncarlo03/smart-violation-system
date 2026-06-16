<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Structural Infrastructure Stats
        $totalUsers = User::count();
        // Assuming role 1 = Student, 2 = CSO, 3 = SAO, 4 = Superadmin
        $activeCSO = User::whereIn('role', [2])->count();
        $activeSAO = User::whereIn('role', [3])->count(); 
        $databasePayload = Violation::count(); // Total records processed by the app

        // 2. Chart 1: Account Distribution by Department (How heavy is each database link)
        $deptStats = Department::select('id', 'name')
            ->withCount('users as total') 
            ->get();

        // 3. Chart 2: Top 5 Active Reporting Officers (Who is utilizing system resources most)
        $topOfficers = Violation::select('cso_id', DB::raw('count(*) as total'))
            ->with('cso:id,name') // Assuming relation 'cso' points to User model
            ->groupBy('cso_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // 4. Table Queue: System Audit Trail (The last 10 raw state changes across CTU-Danao)
        $auditLogs = Violation::with(['student:id,name,id_number,profile_photo', 'cso:id,name', 'department'])
            ->latest()
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalUsers', 
            'activeCSO',
            'activeSAO',
            'databasePayload', 
            'deptStats', 
            'topOfficers', 
            'auditLogs'
        ));
    }
}