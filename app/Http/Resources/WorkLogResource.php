<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_description' => $this->task_description,
            'date' => $this->date,
            'hourly_rate' => $this->hourly_rate,
            'additional_charges' => $this->additional_charges,
            'total_remuneration' => $this->total_remuneration,
            'contributors' => $this->contributors->map(fn ($c) => [
                'id' => $c->id,
                'employee_name' => $c->employee_name,
                'hours_spent' => $c->hours_spent,
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
