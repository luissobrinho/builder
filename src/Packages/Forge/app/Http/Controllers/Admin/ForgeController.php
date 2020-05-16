<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\JobsCreateRequest;
use App\Http\Requests\WorkersCreateRequest;
use App\Http\Requests\WorkersDeleteRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ForgeService;
use Illuminate\View\View;

/**
 * Class ForgeController
 * @package App\Http\Controllers\Admin
 */
class ForgeController extends Controller
{
    /**
     * ForgeController constructor.
     * @param ForgeService $forgeService
     */
    public function __construct(ForgeService $forgeService)
    {
        $this->service = $forgeService;
    }

    /**
     * @return View
     */
    public function index()
    {
        $settings = $this->service->getSettings();
        $firewalls = $this->service->getFirewalls();
        $sites = $this->service->getSites();
        $jobCount = count($this->service->getJobs());
        $workerCount = count($this->service->getWorkers());

        return view('admin.forge.index')->with(compact('settings', 'firewalls', 'sites', 'jobCount', 'workerCount'));
    }

    /**
     * @return View
     */
    public function scheduler()
    {
        $jobs = $this->service->getJobs();
        $site = $this->service->getSite();

        return view('admin.forge.jobs')
            ->with('site', $site)
            ->with('jobs', $jobs);
    }

    /**
     * @return View
     */
    public function workers()
    {
        $workers = $this->service->getWorkers();

        return view('admin.forge.workers')
            ->with('workers', $workers);
    }

    /**
     * @param JobsCreateRequest $request
     * @return RedirectResponse
     */
    public function createJob(JobsCreateRequest $request)
    {
        if ($this->service->createJob($request->all())) {
            return back()->with('message', 'New Job Created');
        }

        return back()->withErrors(['Unable to create Job']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteJob(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'job_id' => 'required',
            ]);

            $this->service->deleteJob($validatedData['job_id']);

            return back()->with('message', 'Job was deleted');
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * @param WorkersCreateRequest $request
     * @return RedirectResponse
     */
    public function createWorker(WorkersCreateRequest $request)
    {
        if ($this->service->createWorker($request->all())) {
            return back()->with('message', 'New Worker Created');
        }

        return back()->withErrors(['Unable to create Worker']);
    }

    /**
     * @param WorkersDeleteRequest $request
     * @return RedirectResponse
     */
    public function deleteWorker(WorkersDeleteRequest $request)
    {
        try {
            $this->service->deleteWorker($request->only('worker_id'));

            return back()->with('message', 'Worker was deleted');
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }
}
