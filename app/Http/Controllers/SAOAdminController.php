<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SAOAdminController extends Controller
{
    public function index()
{
    // 1. Get totals for the Stat Cards
    $totalViolations = \App\Models\Violation::count();
    $pendingReview = \App\Models\Violation::where('status', 'pending')->count();
    $majorIncidents = \App\Models\Violation::whereHas('offense', function($query){
        $query->whereIn('category', ['Serious', 'Very Serious']);
    })
        ->whereDate('updated_at', today())->count();

    // 2. Get the main queue with Relationships (Eager Loading)
    $violations = \App\Models\Violation::with(['student', 'cso', 'department', 'offense'])
                    ->latest()
                    ->paginate(10);

    return view('sao-dashboard', compact('totalViolations', 'pendingReview', 'majorIncidents', 'violations'));
}
}
