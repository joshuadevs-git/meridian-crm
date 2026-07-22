<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PayrollReportExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $employee;

    public function __construct($month = null, $employee = null)
    {
        $this->month = $month;
        $this->employee = $employee;
    }

    public function collection()
    {
        return Payroll::select(
                'employees.employee_no',
                \DB::raw("CONCAT(employees.first_name,' ',employees.last_name) as employee_name"),
                'branches.name as branch',
                'payrolls.payroll_date',
                'payrolls.basic_salary',
                'payrolls.allowance',
                'payrolls.deduction',
                'payrolls.net_salary'
            )
            ->join('employees', 'employees.id', '=', 'payrolls.employee_id')
            ->join('branches', 'branches.id', '=', 'employees.branch_id')

            ->when($this->month, function ($query) {
                $query->whereYear('payroll_date', substr($this->month, 0, 4))
                      ->whereMonth('payroll_date', substr($this->month, 5, 2));
            })

            ->when($this->employee, function ($query) {
                $query->where('employees.id', $this->employee);
            })

            ->get();
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Employee Name',
            'Branch',
            'Payroll Date',
            'Basic Salary',
            'Allowance',
            'Deduction',
            'Net Salary',
        ];
    }
}