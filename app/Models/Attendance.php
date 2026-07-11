<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'time_in',
        'time_out',
        'status',
        'remarks',
    ];

    protected $casts = [
    'attendance_date' => 'date',
    'time_in' => 'datetime:H:i',
    'time_out' => 'datetime:H:i',
];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}