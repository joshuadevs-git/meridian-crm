<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Exports\PayrollReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;use App\Services\PayrollCalculationService;

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


public function calculate(Request $request, PayrollCalculationService $calculator)
{
    // Default to current month
    $month = $request->input('month', now()->format('Y-m'));

    // Validate month format
    $request->validate([
        'month' => [
            'nullable',
            'date_format:Y-m',
        ],
    ]);

    $startDate = Carbon::createFromFormat('Y-m', $month)
        ->startOfMonth();

    $endDate = Carbon::createFromFormat('Y-m', $month)
        ->endOfMonth();

    /*
    |--------------------------------------------------------------------------
    | Get active employees
    |--------------------------------------------------------------------------
    */

    $employees = Employee::where('is_active', true)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Calculate payroll for every employee
    |--------------------------------------------------------------------------
    */

    $calculations = $employees->map(function ($employee) use (
        $calculator,
        $startDate,
        $endDate
    ) {

        $calculation = $calculator->calculate(
            $employee,
            $startDate->toDateString(),
            $endDate->toDateString()
        );

        return [
            'employee' => $employee,
            'calculation' => $calculation,
        ];

    });

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    $totalBasicSalary = $calculations->sum(function ($item) {
        return $item['calculation']['basic_salary'];
    });

    $totalDeductions = $calculations->sum(function ($item) {
        return $item['calculation']['total_deduction'];
    });

    $totalNetSalary = $calculations->sum(function ($item) {
        return $item['calculation']['net_salary'];
    });

    $totalLateMinutes = $calculations->sum(function ($item) {
        return $item['calculation']['late_minutes'];
    });

    $totalAbsentDays = $calculations->sum(function ($item) {
        return $item['calculation']['absent_days'];
    });

    return view('payrolls.calculate', compact(
        'month',
        'startDate',
        'endDate',
        'calculations',
        'totalBasicSalary',
        'totalDeductions',
        'totalNetSalary',
        'totalLateMinutes',
        'totalAbsentDays'
    ));
}


public function saveCalculated(Request $request, PayrollCalculationService $calculator)
{
    $request->validate([
        'period_start' => 'required|date',
        'period_end' => 'required|date|after_or_equal:period_start',
    ]);

    $periodStart = Carbon::parse($request->period_start)->startOfDay();
    $periodEnd = Carbon::parse($request->period_end)->endOfDay();

    // Get active employees
    $employees = Employee::where('is_active', true)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    $saved = 0;
    $skipped = 0;

    foreach ($employees as $employee) {

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate payroll
        |--------------------------------------------------------------------------
        */

        $alreadyExists = Payroll::where('employee_id', $employee->id)
            ->whereDate('period_start', $periodStart->toDateString())
            ->whereDate('period_end', $periodEnd->toDateString())
            ->exists();

        if ($alreadyExists) {
            $skipped++;
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate payroll again
        |--------------------------------------------------------------------------
        */

        $calculation = $calculator->calculate(
            $employee,
            $periodStart->toDateString(),
            $periodEnd->toDateString()
        );

        /*
        |--------------------------------------------------------------------------
        | Save payroll
        |--------------------------------------------------------------------------
        */

        Payroll::create([
            'employee_id' => $employee->id,

            // Payroll date = end of the payroll period
            'payroll_date' => $periodEnd->toDateString(),

            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodEnd->toDateString(),

            'basic_salary' => round($calculation['basic_salary'], 2),

            'allowance' => 0,

            'deduction' => round(
                $calculation['total_deduction'],
                2
            ),

            'net_salary' => round(
                $calculation['net_salary'],
                2
            ),
        ]);

        $saved++;
    }

    return redirect()
        ->route('payrolls.index')
        ->with(
            'success',
            "Payroll saved successfully. {$saved} employee(s) saved."
            . ($skipped > 0
                ? " {$skipped} employee(s) were skipped because payroll already exists for this period."
                : '')
        );
}

}