<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard
     *
     * @return View
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
