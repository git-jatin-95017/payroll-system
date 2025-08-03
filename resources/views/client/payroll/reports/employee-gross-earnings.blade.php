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
                        <option value="{{ route('payroll.reports.employee-earnings') }}" {{ request()->routeIs('payroll.reports.employee-earnings') ? 'selected' : '' }}>Employer Pay</option>
                        <option value="{{ route('reports.employee-gross-earnings', request()->query()) }}" {{ request()->routeIs('reports.employee-gross-earnings') ? 'selected' : '' }}>Employee Gross Earnings</option>
                        <option value="{{ route('payroll.reports.employer-payments') }}" {{ request()->routeIs('payroll.reports.employer-payments') ? 'selected' : '' }}>Employer Earnings</option>
                        <option value="{{ route('reports.statutory-deductions', request()->query()) }}" {{ request()->routeIs('reports.statutory-deductions') ? 'selected' : '' }}>Statutory Deductions</option>
                        <option value="{{ route('reports.additions-deductions', request()->query()) }}" {{ request()->routeIs('reports.additions-deductions') ? 'selected' : '' }}>Additions & Deductions</option>
                        <option value="{{ route('reports.leave', request()->query()) }}" {{ request()->routeIs('reports.leave') ? 'selected' : '' }}>Leave</option>
                        <option value="{{ route('reports.attendance-report', request()->query()) }}" {{ request()->routeIs('reports.attendance-report') ? 'selected' : '' }}>Attendance</option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('reports.download-employee-gross-earnings-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> Excel
                    </a>
                    <a href="{{ route('reports.download-employee-gross-earnings-pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
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
                    <form action="{{ route('reports.employee-gross-earnings') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date', date('Y-m-d', strtotime('-1 week'))) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}">
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
                            <a href="{{ route('reports.employee-gross-earnings') }}" class="btn btn-secondary">Reset</a>
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
                    <h6 class="card-title">Total Gross Pay</h6>
                    <h3 class="mb-0">${{ number_format($earnings->sum('gross'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Net Pay</h6>
                    <h3 class="mb-0">${{ number_format($earnings->sum('net_pay'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Paid Time Off</h6>
                    <h3 class="mb-0">${{ number_format($earnings->sum('paid_time_off'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Employees</h6>
                    <h3 class="mb-0">{{ $earnings->unique('user_id')->count() }}</h3>
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
                                    <th>Pay Type</th>
                                    <th>Rate</th>
                                    <th>Hours/Period</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalHours = 0;
                                    $totalAmount = 0;
                                @endphp
                                @foreach($earnings as $earning)
                                    <tr>
                                        <td>{{ $earning->user->name }}</td>
                                        <td>{{ date('M d, Y', strtotime($earning->start_date)) }} - {{ date('M d, Y', strtotime($earning->end_date)) }}</td>
                                        <td>{{ $earning->user->employeeProfile->pay_type ?? '-' }}</td>
                                        <td>${{ number_format($earning->rate ?? 0, 2) }}</td>
                                        <td>{{ number_format($earning->hours ?? 0, 2) }}</td>
                                        <td>${{ number_format($earning->gross, 2) }}</td>
                                    </tr>
                                    @php
                                        $totalHours += $earning->hours ?? 0;
                                        $totalAmount += $earning->gross;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="4" class="text-end">Subtotals:</td>
                                    <td>{{ number_format($totalHours, 2) }}</td>
                                    <td>${{ number_format($totalAmount, 2) }}</td>
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
@endsection 