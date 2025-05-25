@extends('layouts.new_layout')
@section('content')
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Employee Earnings Report</h3>
                    <!-- <p class="mb-0">What do employees get?</p> -->
                </div>
                <!-- <div>
                    <a href="{{ route('payroll.reports.download-pdf', 'employee-earnings') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payroll.reports.employee-earnings') }}" method="GET" class="row g-3">
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
                            <a href="{{ route('payroll.reports.employee-earnings') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Gross Pay</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('gross'), 2) }}</h3>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Net Pay</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('net_pay'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Paid Time Off</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('paid_time_off'), 2) }}</h3>
                </div>
            </div>
        </div> -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Employees</h6>
                    <h3 class="mb-0">{{ $payrolls->unique('user_id')->count() }}</h3>
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
                                    <th>Gross Pay</th>
                                    <th>Medical Benefits</th>
                                    <th>Social Security</th>
                                    <th>Education Levy</th>
                                    <th>Addition to Net Pay</th>
                                    <th>Deductions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                <tr>
                                    <td>{{ $payroll->user->name }}</td>
                                    <td>{{ date('M d, Y', strtotime($payroll->start_date)) }} - {{ date('M d, Y', strtotime($payroll->end_date)) }}</td>
                                    <td>${{ number_format($payroll->gross, 2) }}</td>
                                    <td>${{ number_format($payroll->medical, 2) }}</td>
                                    <td>${{ number_format($payroll->security, 2) }}</td>
                                    <td>${{ number_format($payroll->edu_levy, 2) }}</td>
                                    <td>${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2) }}</td>
                                    <td>${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2) }}</td>
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