<?php

namespace App\Http\Controllers\User;

use Hash;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\View\View;

class PasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectPath = '/user/password';

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * User wants to change their password
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function password(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return view('user.password')
            ->with('user', $user);
        }

        return back()->withErrors(['Could not find user']);
    }

    /**
     * Change the user's password and return
     *
     * @param  PasswordUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(PasswordUpdateRequest $request)
    {
        $password = $request->new_password;

        if (Hash::check($request->old_password, Auth::user()->password)) {
            $this->resetPassword(Auth::user(), $password);
            return redirect('user/settings')
                ->with('message', 'Password updated successfully');
        }

        return back()->withErrors(['Password could not be updated']);
    }
}
