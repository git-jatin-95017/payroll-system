<!DOCTYPE html>
<html>
<head>
    <title>Additions & Deductions Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .fw-bold { font-weight: bold; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <h2>Additions & Deductions Report</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Pay Period</th>
                <th>Pay Label</th>
                <th>Addition/Deduction</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totals = []; @endphp
            @foreach($earnings as $earning)
                @foreach($earning->additionalEarnings as $additional)
                    @if(!isset($payLabelFilter) || $additional->payhead_id == $payLabelFilter)
                    <tr>
                        <td>{{ $earning->user->name ?? '' }}</td>
                        <td>{{ date('M d, Y', strtotime($earning->start_date)) }} - {{ date('M d, Y', strtotime($earning->end_date)) }}</td>
                        <td>{{ $additional->payhead->name ?? '' }}</td>
                        <td>{{ $additional->payhead->pay_type == 'nothing' ? 'Addition' : 'Deduction' }}</td>
                        <td>${{ number_format($additional->amount, 2) }}</td>
                    </tr>
                    @php
                        $payLabel = $additional->payhead->pay_label ?? '';
                        $totals[$payLabel] = ($totals[$payLabel] ?? 0) + $additional->amount;
                    @endphp
                    @endif
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="4" class="text-end">Subtotals:</td>
                <td>
                    @foreach($totals as $label => $total)
                        <div>{{ $label }}: ${{ number_format($total, 2) }}</div>
                    @endforeach
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html> 