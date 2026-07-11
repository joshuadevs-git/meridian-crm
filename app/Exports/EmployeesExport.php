<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::with('branch')
            ->get()
            ->map(function ($employee) {

                return [
                    'Employee No' => $employee->employee_no,
                    'First Name' => $employee->first_name,
                    'Last Name' => $employee->last_name,
                    'Email' => $employee->email,
                    'Phone' => $employee->phone,
                    'Branch' => $employee->branch?->name,
                    'Department' => $employee->department,
                    'Position' => $employee->position,
                    'Hire Date' => $employee->hire_date?->format('Y-m-d'),
                    'Salary' => $employee->salary,
                    'Status' => $employee->is_active ? 'Active' : 'Inactive',
                ];

            });
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Branch',
            'Department',
            'Position',
            'Hire Date',
            'Salary',
            'Status',
        ];
    }
}