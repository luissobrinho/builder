<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * @var UserService
     */
    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * View current user's settings
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return view('user.settings')
            ->with('user', $user);
        }

        return back()->withErrors(['Could not find user']);
    }

    /**
     * Update the user
     *
     * @param  UserUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request)
    {
        try {
            if ($this->service->update(auth()->id(), $request->all())) {
                return back()->with('message', 'Settings updated successfully');
            }

            return back()->withErrors(['Could not update user']);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
