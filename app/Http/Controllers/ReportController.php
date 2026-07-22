<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function attendance(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $branch = $request->branch;
        $search = $request->search;

        $attendanceReport = Attendance::select(
                'employee_id',
                DB::raw("SUM(status='Present') as present"),
                DB::raw("SUM(status='Late') as late"),
                DB::raw("SUM(status='Absent') as absent"),
                DB::raw("SUM(status='Leave') as leave_count")
            )
            ->with('employee.branch')

            ->when($month, function ($query) use ($month) {

                $query->whereYear('attendance_date', substr($month,0,4))
                      ->whereMonth('attendance_date', substr($month,5,2));

            })

            ->when($branch, function ($query) use ($branch) {

                $query->whereHas('employee', function ($q) use ($branch) {

                    $q->where('branch_id', $branch);

                });

            })

            ->when($search, function ($query) use ($search) {

                $query->whereHas('employee', function ($q) use ($search) {

                    $q->where('employee_no', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");

                });

            })

            ->groupBy('employee_id')
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::orderBy('name')->get();

        return view('reports.attendance', compact(
            'attendanceReport',
            'branches',
            'month',
            'branch',
            'search'
        ));
    }



    public function exportExcel(Request $request)
{
    return Excel::download(

        new AttendanceReportExport(

            $request->month,
            $request->branch,
            $request->search

        ),

        'attendance-report.xlsx'

    );
}

public function exportCsv(Request $request)
{
    return Excel::download(

        new AttendanceReportExport(

            $request->month,
            $request->branch,
            $request->search

        ),

        'attendance-report.csv'

    );
}



public function exportPdf(Request $request)
{
    $month = $request->month ?? now()->format('Y-m');
    $branch = $request->branch;
    $search = $request->search;

    $attendanceReport = (new AttendanceReportExport(
        $month,
        $branch,
        $search
    ))->collection();

    $pdf = Pdf::loadView(
        'reports.attendance-pdf',
        compact('attendanceReport')
    );

    return $pdf->download('attendance-report.pdf');
}

}


