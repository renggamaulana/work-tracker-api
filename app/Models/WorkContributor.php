<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkContributor extends Model
{
     protected $fillable = ['employee_name', 'hours_spent'];

    public function workLog()
    {
        return $this->belongsTo(WorkLog::class);
    }
}
