<?php

namespace App\Http\Controllers\Auth;

use App\Services\UserService;
use App\Services\ActivateService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActivateController extends Controller
{
    /**
     * @var ActivateService
     */
    private $service;

    /**
     * Create a new controller instance.
     *
     * @param ActivateService $activateService
     */
    public function __construct(ActivateService $activateService)
    {
        $this->service = $activateService;
    }

    /**
     * Inform the user they must activate thier account
     *
     * @return View
     */
    public function showActivate()
    {
        return view('auth.activate.email');
    }

    /**
     * Send a new token for activation
     *
     * @return View
     */
    public function sendToken()
    {
        $this->service->sendActivationToken();
        return view('auth.activate.token');
    }

    /**
     * Activate a user account
     *
     * @return RedirectResponse|View
     */
    public function activate($token)
    {
        if ($this->service->activateUser($token)) {
            return redirect('dashboard')->with('message', 'Your account was activated');
        }

        return view('auth.activate.email')->withErrors(['Could not validate your token']);
    }
}
