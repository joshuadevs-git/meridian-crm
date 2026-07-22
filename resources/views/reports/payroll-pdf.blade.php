<!DOCTYPE html>
<html>
<head>
    <title>Payroll Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<h2>Payroll Report</h2>

<table>
    <thead>
        <tr>
            <th>Employee No</th>
            <th>Employee Name</th>
            <th>Branch</th>
            <th>Payroll Date</th>
            <th>Basic Salary</th>
            <th>Allowance</th>
            <th>Deduction</th>
            <th>Net Salary</th>
        </tr>
    </thead>

    <tbody>
        @foreach($payrolls as $payroll)
        <tr>
            <td>{{ $payroll->employee->employee_no ?? '' }}</td>
            <td>
                {{ $payroll->employee->first_name ?? '' }}
                {{ $payroll->employee->last_name ?? '' }}
            </td>
            <td>{{ $payroll->employee->branch->name ?? '' }}</td>
            <td>{{ $payroll->payroll_date }}</td>
            <td>{{ number_format($payroll->basic_salary, 2) }}</td>
            <td>{{ number_format($payroll->allowance, 2) }}</td>
            <td>{{ number_format($payroll->deduction, 2) }}</td>
            <td>{{ number_format($payroll->net_salary, 2) }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

</body>
</html>