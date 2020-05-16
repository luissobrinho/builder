<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FeatureService;
use App\Http\Requests\FeatureCreateRequest;
use App\Http\Requests\FeatureUpdateRequest;
use Illuminate\View\View;

class FeatureController extends Controller
{
    public function __construct(FeatureService $featureService)
    {
        $this->service = $featureService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $features = $this->service->paginated();
        return view('admin.features.index')->with('features', $features);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @param Request $request
     * @return View
     */
    public function search(Request $request)
    {
        $features = $this->service->search($request->get('search'), null);
        return view('admin.features.index')->with('features', $features);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.features.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FeatureCreateRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(FeatureCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result && is_object($result)) {
            return redirect('admin/features/'.$result->getAttribute('id').'/edit')->with('message', 'Successfully created');
        } elseif ($result) {
            return redirect('admin/features')->with('message', 'Successfully created');
        }

        return redirect('admin/features')->with('errors', ['Failed to create']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $feature = $this->service->find($id);
        return view('admin.features.edit')->with('feature', $feature);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FeatureUpdateRequest  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(FeatureUpdateRequest $request, $id)
    {
        $result = $this->service->update($id, $request->except('_token'));

        if ($result) {
            return back()->with('message', 'Successfully updated');
        }

        return back()->with('errors', ['Failed to update']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            return redirect('admin/features')->with('message', 'Successfully deleted');
        }

        return redirect('admin/features')->with('errors', ['Failed to delete']);
    }
}
