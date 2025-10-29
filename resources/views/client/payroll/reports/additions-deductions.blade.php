@extends('layouts.new_layout')
@section('content')
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Reports</h3>
                </div>
                <div>
                    <select class="form-select" name="reports" id="reportSelect">
                        <option value="">Reports</option>
                        <option value="{{ route('payroll.reports.employee-earnings') }}" {{ request()->routeIs('payroll.reports.employee-earnings') ? 'selected' : '' }}>Employee Pay</option>
                        <option value="{{ route('reports.employee-gross-earnings', request()->query()) }}" {{ request()->routeIs('reports.employee-gross-earnings') ? 'selected' : '' }}>Employee Gross Earnings</option>
                        <option value="{{ route('payroll.reports.employer-payments') }}" {{ request()->routeIs('payroll.reports.employer-payments') ? 'selected' : '' }}>Employer Pay</option>
                        <option value="{{ route('reports.statutory-deductions', request()->query()) }}" {{ request()->routeIs('reports.statutory-deductions') ? 'selected' : '' }}>Statutory Deductions</option>
                        <option value="{{ route('reports.additions-deductions', request()->query()) }}" {{ request()->routeIs('reports.additions-deductions') ? 'selected' : '' }}>Additions & Deductions</option>
                        <option value="{{ route('reports.leave', request()->query()) }}" {{ request()->routeIs('reports.leave') ? 'selected' : '' }}>Leave</option>
                        <option value="{{ route('reports.attendance-report', request()->query()) }}" {{ request()->routeIs('reports.attendance-report') ? 'selected' : '' }}>Attendance</option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('reports.download-additions-deductions-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> Excel
                    </a>
                    <a href="{{ route('reports.download-additions-deductions-pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reports.additions-deductions') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date', date('Y-m-d', strtotime('-1 week'))) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department_id">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->dep_name }}" {{ request('department_id') == $department->dep_name ? 'selected' : '' }}>
                                        {{ $department->dep_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Employee</label>
                            <select class="form-select" name="employee_id">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pay Label</label>
                            <select class="form-select" name="pay_label">
                                <option value="">All Pay Labels</option>
                                @foreach($payLabels as $label)
                                    <option value="{{ $label->id }}" {{ request('pay_label') == $label->id ? 'selected' : '' }}>
                                        {{ $label->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('reports.additions-deductions') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                @php
                                    $totals = [];
                                @endphp
                                @foreach($earnings as $earning)
                                    @foreach($earning->additionalEarnings as $additional)
                                        @if(!request('pay_label') || $additional->payhead_id == request('pay_label'))
                                        <tr>
                                            <td>{{ $earning->user->name }}</td>
                                            <td>{{ date('M d, Y', strtotime($earning->start_date)) }} - {{ date('M d, Y', strtotime($earning->end_date)) }}</td>
                                            <td>{{ $additional->payhead->name }}</td>
                                            <td>{{ $additional->payhead->pay_type == 'nothing' ? 'Addition' : 'Deduction' }}</td>
                                            <td>${{ number_format($additional->amount, 2) }}</td>
                                        </tr>
                                        @php
                                            $payLabel = $additional->payhead->pay_label;
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('reportSelect').addEventListener('change', function() {
        const selectedUrl = this.value;
        if (selectedUrl) {
            window.location.href = selectedUrl;
        }
    });
</script>
@endpush
@endsection 