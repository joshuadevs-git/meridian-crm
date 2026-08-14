<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'payroll_date',
        'period_start',
        'period_end',
        'basic_salary',
        'allowance',
        'deduction',
        'net_salary',
    ];

    protected $casts = [
        'payroll_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}