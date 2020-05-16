<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    private $service;

    public function __construct(RoleService $roleService)
    {
        $this->service = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $roles = $this->service->all();
        return view('admin.roles.index')->with('roles', $roles);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function search(Request $request)
    {
        if (! $request->get('search')) {
            return redirect('admin/roles');
        }

        $roles = $this->service->search($request->get('search'));
        return view('admin.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Show the form for inviting a customer.
     *
     * @param RoleCreateRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(RoleCreateRequest $request)
    {
        $result = $this->service->create($request->except(['_token', '_method']));

        if ($result) {
            return redirect('admin/roles')->with('message', 'Successfully created');
        }

        return back()->with('error', 'Failed to invite');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $role = $this->service->find($id);
        return view('admin.roles.edit')->with('role', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            return back()->with('message', 'Successfully updated');
        }

        return back()->with('error', 'Failed to update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('admin/roles')->with('message', 'Successfully deleted');
        }

        return redirect('admin/roles')->with('error', 'Failed to delete');
    }
}
