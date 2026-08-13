<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Exports\PayrollReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('employee')
            ->latest()
            ->paginate(10);

        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_date' => 'required|date',
            'basic_salary' => 'required|numeric',
            'allowance' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);

        $netSalary =
            $request->basic_salary +
            ($request->allowance ?? 0) -
            ($request->deduction ?? 0);

        Payroll::create([
            'employee_id' => $request->employee_id,
            'payroll_date' => $request->payroll_date,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance ?? 0,
            'deduction' => $request->deduction ?? 0,
            'net_salary' => $netSalary,
        ]);

        return redirect()
            ->route('payrolls.index')
            ->with('success', 'Payroll created successfully.');
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('payrolls.edit', compact(
            'payroll',
            'employees'
        ));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_date' => 'required|date',
            'basic_salary' => 'required|numeric',
            'allowance' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);

        $netSalary =
            $request->basic_salary +
            ($request->allowance ?? 0) -
            ($request->deduction ?? 0);

        $payroll->update([
            'employee_id' => $request->employee_id,
            'payroll_date' => $request->payroll_date,
            'basic_salary' => $request->basic_salary,
            'allowance' => $request->allowance ?? 0,
            'deduction' => $request->deduction ?? 0,
            'net_salary' => $netSalary,
        ]);

        return redirect()
            ->route('payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()
            ->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }



    public function report(Request $request)
{
    $month = $request->month;
    $employee = $request->employee;

    $payrolls = Payroll::with('employee.branch')

        ->when($month, function ($query) use ($month) {

            $query->whereYear('payroll_date', substr($month, 0, 4))
                  ->whereMonth('payroll_date', substr($month, 5, 2));

        })

        ->when($employee, function ($query) use ($employee) {

            $query->where('employee_id', $employee);

        })

        ->latest()
        ->paginate(10)
        ->withQueryString();

    $employees = Employee::orderBy('first_name')->get();

    return view('reports.payroll', compact(
        'payrolls',
        'employees',
        'month',
        'employee'
    ));
}

public function exportExcel(Request $request)
{
    return Excel::download(

        new PayrollReportExport(
            $request->month,
            $request->employee
        ),

        'payroll-report.xlsx'

    );
}

public function exportCsv(Request $request)
{
    return Excel::download(

        new PayrollReportExport(
            $request->month,
            $request->employee
        ),

        'payroll-report.csv'

    );
}

public function exportPdf()
{
    $payrolls = Payroll::with('employee.branch')
        ->latest()
        ->get();

    $pdf = Pdf::loadView(
        'reports.payroll-pdf',
        compact('payrolls')
    );

    return $pdf->download('payroll-report.pdf');
}


public function myPayroll()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $payrolls = $employee->payrolls()
        ->latest('payroll_date')
        ->latest('id')
        ->paginate(10);

    return view('employee.payroll', compact('employee', 'payrolls'));
}

public function generate(Request $request)
{
    $request->validate([
        'payroll_date' => 'required|date',
    ]);

    $payrollDate = Carbon::parse($request->payroll_date);

    $year = $payrollDate->year;
    $month = $payrollDate->month;

    /*
    |--------------------------------------------------------------------------
    | Get active employees
    |--------------------------------------------------------------------------
    */

    $employees = Employee::where('is_active', true)
        ->get();

    if ($employees->isEmpty()) {
        return back()
            ->with('error', 'No active employees found.');
    }

    /*
    |--------------------------------------------------------------------------
    | Count Monday-Friday working days
    |--------------------------------------------------------------------------
    */

    $workingDays = 0;

    $date = Carbon::create($year, $month, 1);

    while ($date->month === $month) {

        if ($date->isWeekday()) {
            $workingDays++;
        }

        $date->addDay();
    }

    if ($workingDays <= 0) {
        return back()
            ->with('error', 'No working days found for this month.');
    }

    /*
    |--------------------------------------------------------------------------
    | Generate payroll for every employee
    |--------------------------------------------------------------------------
    */

    foreach ($employees as $employee) {

        $basicSalary = (float) ($employee->salary ?? 0);

        if ($basicSalary <= 0) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate payroll for same employee/month
        |--------------------------------------------------------------------------
        */

        $alreadyExists = Payroll::where('employee_id', $employee->id)
            ->whereYear('payroll_date', $year)
            ->whereMonth('payroll_date', $month)
            ->exists();

        if ($alreadyExists) {
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Daily rate
        |--------------------------------------------------------------------------
        */

        $dailyRate = $basicSalary / $workingDays;

        /*
        |--------------------------------------------------------------------------
        | Get attendance records
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->get();

        $absentDays = $attendances
            ->where('status', 'Absent')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Calculate late minutes
        |--------------------------------------------------------------------------
        |
        | Assumption:
        | Regular work starts at 8:00 AM.
        |
        */

        $lateMinutes = 0;

        foreach ($attendances as $attendance) {

            if (!$attendance->time_in) {
                continue;
            }

            if ($attendance->status !== 'Late') {
                continue;
            }

            $timeIn = Carbon::parse($attendance->time_in);

            $scheduledTime = Carbon::create(
                $timeIn->year,
                $timeIn->month,
                $timeIn->day,
                8,
                0,
                0
            );

            if ($timeIn->greaterThan($scheduledTime)) {

                $lateMinutes += $scheduledTime->diffInMinutes($timeIn);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate deductions
        |--------------------------------------------------------------------------
        */

        // Absent deduction
        $absentDeduction = $dailyRate * $absentDays;

        // Hourly rate
        $hourlyRate = $dailyRate / 8;

        // Per-minute rate
        $minuteRate = $hourlyRate / 60;

        // Late deduction
        $lateDeduction = $minuteRate * $lateMinutes;

        // Total deduction
        $totalDeduction = $absentDeduction + $lateDeduction;

        /*
        |--------------------------------------------------------------------------
        | Net salary
        |--------------------------------------------------------------------------
        */

        $allowance = 0;

        $netSalary =
            $basicSalary
            + $allowance
            - $totalDeduction;

        /*
        |--------------------------------------------------------------------------
        | Create payroll
        |--------------------------------------------------------------------------
        */

        Payroll::create([
            'employee_id' => $employee->id,
            'payroll_date' => $payrollDate->toDateString(),
            'basic_salary' => $basicSalary,
            'allowance' => $allowance,
            'deduction' => round($totalDeduction, 2),
            'net_salary' => round($netSalary, 2),
        ]);
    }

    return redirect()
        ->route('payrolls.index')
        ->with(
            'success',
            'Payroll generated successfully for ' .
            $payrollDate->format('F Y') .
            '.'
        );
}

}