<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
     protected $fillable = [
        'task_description',
        'date',
        'hourly_rate',
        'additional_charges',
        'total_remuneration',
    ];

    public function contributors()
    {
        return $this->hasMany(WorkContributor::class);
    }
}
