@extends('layouts.new_layout')
@section('content')
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Reports</h3>
                    <!-- <p class="mb-0">What Employer pays?</p> -->
                </div>
                <div>
                    <select class="form-select" name="reports" id="reportSelect">
                        <option value="">Reports</option>
                        <option value="{{ route('payroll.reports.employee-earnings') }}" {{ request()->routeIs('payroll.reports.employee-earnings') ? 'selected' : '' }}>Employee Earnings</option>
                        <option value="{{ route('reports.employee-gross-earnings', request()->query()) }}" {{ request()->routeIs('reports.employee-gross-earnings') ? 'selected' : '' }}>Employee Gross Earnings</option>
                        <option value="{{ route('payroll.reports.employer-payments') }}" {{ request()->routeIs('payroll.reports.employer-payments') ? 'selected' : '' }}>Employer Earnings</option>
                        <option value="{{ route('reports.statutory-deductions', request()->query()) }}" {{ request()->routeIs('reports.statutory-deductions') ? 'selected' : '' }}>Statutory Deductions</option>
                        <option value="{{ route('reports.additions-deductions', request()->query()) }}" {{ request()->routeIs('reports.additions-deductions') ? 'selected' : '' }}>Additions & Deductions</option>
                        <option value="{{ route('reports.leave', request()->query()) }}" {{ request()->routeIs('reports.leave') ? 'selected' : '' }}>Leave</option>
                        <option value="{{ route('reports.attendance-report', request()->query()) }}" {{ request()->routeIs('reports.attendance-report') ? 'selected' : '' }}>Attendance</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('payroll.reports.download-report-excel', 'employer-payments') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('payroll.reports.download-pdf', 'employer-payments') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Export PDF
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
                    <form action="{{ route('payroll.reports.employer-payments') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', date('Y-m-d', strtotime('-1 week'))) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
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
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('payroll.reports.employer-payments') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <!-- <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Medical Benefits</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('medical'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Social Security</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('security_employer'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Education Levy</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('edu_levy'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Employer Burden</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('medical') + $payrolls->sum('security_employer') + $payrolls->sum('edu_levy'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div> -->

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
                                    <th>Employee Pay</th>
                                    <th>Employee Taxes</th>
                                    <th>Employer Taxes</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                @php
                                    $gross = $payroll->gross + $payroll->paid_time_off;
                                    $mbse_deductions = $payroll->medical + $payroll->security + $payroll->edu_levy;
                                    $nothingAdditionTonetPay = $payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount');
                                    $deductions = $payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount');
                                    $employeePay = $gross - $mbse_deductions + $nothingAdditionTonetPay - $deductions;
                                @endphp
                                <tr>
                                    <td>{{ $payroll->user->name }}</td>
                                    <td>{{ date('M d, Y', strtotime($payroll->start_date)) }} - {{ date('M d, Y', strtotime($payroll->end_date)) }}</td>
                                    <td>${{ number_format($employeePay, 2) }}</td>
                                    <td>${{ number_format($payroll->medical + $payroll->security + $payroll->edu_levy, 2) }}</td>
                                    <td>${{ number_format($payroll->medical + $payroll->security_employer, 2) }}</td>
                                    <td>${{ number_format($employeePay + $payroll->medical + $payroll->security + $payroll->edu_levy + $payroll->medical + $payroll->security_employer, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var reportSelect = document.getElementById('reportSelect');
    if (reportSelect) {
        reportSelect.addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });
    }
});
</script>
@endpush 