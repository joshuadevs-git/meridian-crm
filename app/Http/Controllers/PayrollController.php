<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
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

}