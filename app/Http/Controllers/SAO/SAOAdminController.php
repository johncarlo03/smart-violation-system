<?php

namespace App\Http\Controllers\SAO;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SAOAdminController extends Controller
{
    public function index()
    {
        return view('sao.dashboard');
    }
}