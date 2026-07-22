<!DOCTYPE html>
<html>
<head>
    <title>Leave Report</title>

    <style>
        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:5px;
        }
    </style>
</head>
<body>

<h2>Leave Report</h2>

<table>

    <thead>
        <tr>
            <th>Employee No</th>
            <th>Employee</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    @foreach($leaveReport as $row)

        <tr>
            <td>{{ $row['employee_no'] }}</td>
            <td>{{ $row['employee'] }}</td>
            <td>{{ $row['leave_type'] }}</td>
            <td>{{ $row['start_date'] }}</td>
            <td>{{ $row['end_date'] }}</td>
            <td>{{ $row['status'] }}</td>
        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>