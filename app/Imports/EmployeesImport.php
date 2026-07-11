<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $branch = Branch::where('name', $row['branch'])->first();

        return new Employee([
            'employee_no' => $row['employee_no'],
            'first_name'  => $row['first_name'],
            'last_name'   => $row['last_name'],
            'email'       => $row['email'],
            'phone'       => $row['phone'],
            'branch_id'   => $branch?->id,
            'department'  => $row['department'],
            'position'    => $row['position'],
            'hire_date'   => $row['hire_date'],
            'salary'      => $row['salary'],
            'is_active'   => strtolower($row['status']) === 'active',
        ]);
    }
}