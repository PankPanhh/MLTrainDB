<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenkinsJob extends Model
{
    protected $fillable = [
        'job_name',
        'status',
        'log',
        'triggered_at',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
    ];
}
