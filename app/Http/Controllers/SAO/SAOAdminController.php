<?php

namespace App\Http\Controllers\SAO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SAOAdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 4) {
            return view('admin-dashboard');
        }

        return view('sao.dashboard');
    }
}