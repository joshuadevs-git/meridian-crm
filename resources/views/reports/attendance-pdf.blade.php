<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
<body>

<h2>Attendance Report</h2>

<table>
    <thead>
        <tr>
            <th>Employee No</th>
            <th>Employee</th>
            <th>Branch</th>
            <th>Present</th>
            <th>Late</th>
            <th>Absent</th>
            <th>Leave</th>
        </tr>
    </thead>

    <tbody>
        @foreach($attendanceReport as $row)
        <tr>
            <td>{{ $row->employee_no }}</td>
            <td>{{ $row->employee }}</td>
            <td>{{ $row->branch }}</td>
            <td>{{ $row->present }}</td>
            <td>{{ $row->late }}</td>
            <td>{{ $row->absent }}</td>
            <td>{{ $row->leave_count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>