<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * Homepage
     *
     * @return View
     */
    public function home()
    {
        return view('welcome');
    }

    /**
     * Dashboard
     *
     * @return View
     */
    public function dashboard()
    {
        return view('dashboard');
    }
}
