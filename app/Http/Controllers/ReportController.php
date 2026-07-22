<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LeaveReportExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;

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


public function leaveReport(Request $request)
{
    $month = $request->month;
    $status = $request->status;
    $search = $request->search;

    $leaveReport = \App\Models\Leave::with('employee')

        ->when($month, function ($q) use ($month) {
            $q->whereYear('start_date', substr($month, 0, 4))
              ->whereMonth('start_date', substr($month, 5, 2));
        })

        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })

        ->when($search, function ($q) use ($search) {
            $q->whereHas('employee', function ($x) use ($search) {
                $x->where('employee_no', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        })

        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('reports.leaves', compact(
        'leaveReport',
        'month',
        'status',
        'search'
    ));
}



public function leaveExcel(Request $request)
{
    return Excel::download(
        new LeaveReportExport(
            $request->month,
            $request->status,
            $request->search
        ),
        'leave-report.xlsx'
    );
}

public function leaveCsv(Request $request)
{
    return Excel::download(
        new LeaveReportExport(
            $request->month,
            $request->status,
            $request->search
        ),
        'leave-report.csv'
    );
}

public function leavePdf(Request $request)
{
    $leaveReport = (new LeaveReportExport(
        $request->month,
        $request->status,
        $request->search
    ))->collection();

    $pdf = Pdf::loadView(
        'reports.leave-pdf',
        compact('leaveReport')
    );

    return $pdf->download('leave-report.pdf');
}

}






