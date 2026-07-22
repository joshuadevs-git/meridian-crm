<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceReportExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $branch;
    protected $search;

    public function __construct($month, $branch, $search)
    {
        $this->month = $month;
        $this->branch = $branch;
        $this->search = $search;
    }

    public function collection()
    {
        return Attendance::select(
    'employees.employee_no',
    DB::raw("CONCAT(employees.first_name,' ',employees.last_name) as employee"),
    'branches.name as branch',
    DB::raw("SUM(status='Present') as present"),
    DB::raw("SUM(status='Late') as late"),
    DB::raw("SUM(status='Absent') as absent"),
    DB::raw("SUM(status='Leave') as leave_count")
)
            ->join('employees','employees.id','=','attendances.employee_id')
            ->join('branches','branches.id','=','employees.branch_id')

            ->when($this->month,function($q){

                $q->whereYear('attendance_date',substr($this->month,0,4))
                  ->whereMonth('attendance_date',substr($this->month,5,2));

            })

            ->when($this->branch,function($q){

                $q->where('branches.id',$this->branch);

            })

            ->when($this->search,function($q){

                $q->where(function($x){

                    $x->where('employees.employee_no','like',"%{$this->search}%")
                      ->orWhere('employees.first_name','like',"%{$this->search}%")
                      ->orWhere('employees.last_name','like',"%{$this->search}%");

                });

            })

            ->groupBy(
                'employees.employee_no',
                'employees.first_name',
                'employees.last_name',
                'branches.name'
            )

            ->get();
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Employee',
            'Branch',
            'Present',
            'Late',
            'Absent',
            'Leave',
        ];
    }
}