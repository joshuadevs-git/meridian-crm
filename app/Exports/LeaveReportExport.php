<?php

namespace App\Exports;

use App\Models\Leave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveReportExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $status;
    protected $search;

    public function __construct($month, $status, $search)
    {
        $this->month = $month;
        $this->status = $status;
        $this->search = $search;
    }

    public function collection()
    {
        return Leave::with('employee')

            ->when($this->month, function ($q) {
                $q->whereYear('start_date', substr($this->month, 0, 4))
                  ->whereMonth('start_date', substr($this->month, 5, 2));
            })

            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })

            ->when($this->search, function ($q) {
                $q->whereHas('employee', function ($x) {
                    $x->where('employee_no', 'like', "%{$this->search}%")
                      ->orWhere('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%");
                });
            })

            ->get()

            ->map(function ($leave) {
                return [
                    'employee_no' => $leave->employee->employee_no,
                    'employee' => $leave->employee->first_name.' '.$leave->employee->last_name,
                    'leave_type' => $leave->leave_type,
                    'start_date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'status' => $leave->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Employee',
            'Leave Type',
            'Start Date',
            'End Date',
            'Status',
        ];
    }
}