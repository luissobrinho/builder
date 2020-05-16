<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FailedJob extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';

    public function getNameAttribute()
    {
        return json_decode($this->payload)->displayName;
    }

    public function getReasonAttribute()
    {
        return Str::limit($this->exception, 40);
    }
}
