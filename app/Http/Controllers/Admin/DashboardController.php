<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        DashboardService $dashboard
    ) {
        return view(
            'admin.dashboard',
            $dashboard->getDashboardData($request)
        );
    }
    
}