<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LogService;
use Illuminate\View\View;

class LogsController extends Controller
{
    /**
     * @var LogService
     */
    private $service;

    public function __construct(LogService $logService)
    {
        $this->service = $logService;
    }

    /**
     * Display a listing of the logs.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $logs = $this->service->get($request->all());
        $levels = $this->service->levels;
        $dates = $this->service->getLogDates();

        return view('admin.logs.index')
            ->with('dates', $dates)
            ->with('logs', $logs)
            ->with('levels', $levels);
    }
}
