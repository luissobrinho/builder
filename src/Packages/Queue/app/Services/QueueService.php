<?php

namespace App\Services;

use App\Models\FailedJob;
use App\Models\Job;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class QueueService
{
    /**
     * @var int
     */
    public $pagination;
    /**
     * @var Job
     */
    private $job;
    /**
     * @var FailedJob
     */
    private $failedJob;

    /**
     * QueueService constructor.
     * @param Job $job
     * @param FailedJob $failedJob
     */
    public function __construct(Job $job, FailedJob $failedJob)
    {
        $this->job = $job;
        $this->failedJob = $failedJob;
        $this->pagination = 25;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function activeJobs()
    {
        return $this->job->where('reserved_at', '!=', null)->paginate($this->pagination);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function upcomingJobs()
    {
        return $this->job->paginate($this->pagination);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function failedJobs()
    {
        return $this->failedJob->paginate($this->pagination);
    }

    /**
     * @return bool
     */
    public function restart()
    {
        try {
            Artisan::call('queue:restart');

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @return bool
     */
    public function retryAll()
    {
        try {
            Artisan::call('queue:retry', [
                'id' => 'all',
            ]);

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param $id
     * @param $table
     * @return boolean
     * @throws Exception
     */
    public function cancel($id, $table)
    {
        if ($table === 'failed') {
            return $this->failedJob->find($id)->delete();
        }

        return $this->find($id)->delete();
    }

    /**
     * @param $id
     * @return bool
     */
    public function retry($id)
    {
        try {
            Artisan::call('queue:retry', [
                'id' => $id,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param $id
     * @return Job
     */
    public function find($id)
    {
        return $this->job->find($id);
    }
}
