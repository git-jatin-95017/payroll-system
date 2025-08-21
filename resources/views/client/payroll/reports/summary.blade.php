@extends('layouts.new_layout')
@section('content')
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Payroll Summary Report</h3>
                    <p class="mb-0">View and analyze payroll data</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payroll.reports.summary') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department_id">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pay Type</label>
                            <select class="form-select" name="pay_type">
                                <option value="">All Types</option>
                                <option value="hourly" {{ request('pay_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                <option value="weekly" {{ request('pay_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="bi-weekly" {{ request('pay_type') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                <option value="monthly" {{ request('pay_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="payment_method">
                                <option value="">All Methods</option>
                                <option value="direct_deposit" {{ request('payment_method') == 'direct_deposit' ? 'selected' : '' }}>Direct Deposit</option>
                                <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('payroll.reports.summary') }}" class="btn btn-secondary">Reset</a>
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
                    <h6 class="card-title">Total Payroll</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('gross'), 2,'.','') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Deductions</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('medical') + $payrolls->sum('security') + $payrolls->sum('edu_levy'), 2,'.','') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Net Pay</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('net_pay'), 2,'.','') }}</h3>
                </div>
            </div>
        </div>
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
                                    <th>Department</th>
                                    <th>Pay Type</th>
                                    <th>Gross Pay</th>
                                    <th>Medical</th>
                                    <th>Social Security</th>
                                    <th>Education Levy</th>
                                    <th>Net Pay</th>
                                    <th>Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                <tr>
                                    <td>{{ $payroll->user->name }}</td>
                                    <td>{{ $payroll->user->department->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($payroll->user->employeeProfile->pay_type) }}</td>
                                    <td>${{ number_format($payroll->gross, 2,'.','') }}</td>
                                    <td>${{ number_format($payroll->medical, 2,'.','') }}</td>
                                    <td>${{ number_format($payroll->security, 2,'.','') }}</td>
                                    <td>${{ number_format($payroll->edu_levy, 2,'.','') }}</td>
                                    <td>${{ number_format($payroll->net_pay, 2,'.','') }}</td>
                                    <td>{{ ucfirst($payroll->payment_method) }}</td>
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