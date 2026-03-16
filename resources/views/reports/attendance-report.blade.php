<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h3>{{ $title }}</h3>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Shift</th>
            <th>Clock In</th>
            <th>Clock Out</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $a)
            <tr>
                <td>{{ $a->user->name }}</td>
                <td>{{ $a->shift }}</td>
                <td>{{ $a->clock_in }}</td>
                <td>{{ $a->clock_out }}</td>
                <td>{{ $a->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
