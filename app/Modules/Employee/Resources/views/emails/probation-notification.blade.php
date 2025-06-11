<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $months }}-Month Contract Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 90%;
            margin: auto;
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .info {
            background: #fff;
            padding: 15px;
            border: 1px solid #ccc;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">{{ $months }}-Month Contract Notification</div>
        <div class="info">
            <p><strong>Employee Name:</strong> {{ $employee->name }}</p>
            <p><strong>Start Date:</strong>
                {{ \Carbon\Carbon::parse($employee->probation_start_date)->format('F j, Y') }}</p>

            <p>{{ $employee->name }}'s probation period has reached <strong>{{ $months }}
                    month{{ $months > 1 ? 's' : '' }}</strong>.</p>


            @if (!empty($employee->department_id))
                <p><strong>Sub-Function:</strong> {{ optional($employee->department)->title ?? 'N/A' }}</p>
            @endif
        </div>
    </div>
</body>

</html>
