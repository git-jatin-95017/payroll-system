@extends('layouts.new_layout')
@section('content')
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Tax and Deductions Report</h3>
                    <p class="mb-0">View and analyze tax calculations and deductions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payroll.reports.taxes') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tax Type</label>
                            <select class="form-select" name="tax_type">
                                <option value="">All Taxes</option>
                                <option value="medical" {{ request('tax_type') == 'medical' ? 'selected' : '' }}>Medical Benefits</option>
                                <option value="social_security" {{ request('tax_type') == 'social_security' ? 'selected' : '' }}>Social Security</option>
                                <option value="education_levy" {{ request('tax_type') == 'education_levy' ? 'selected' : '' }}>Education Levy</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Age Group</label>
                            <select class="form-select" name="age_group">
                                <option value="">All Ages</option>
                                <option value="under_60" {{ request('age_group') == 'under_60' ? 'selected' : '' }}>Under 60</option>
                                <option value="60_to_70" {{ request('age_group') == '60_to_70' ? 'selected' : '' }}>60-70</option>
                                <option value="over_70" {{ request('age_group') == 'over_70' ? 'selected' : '' }}>Over 70</option>
                            </select>
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
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('payroll.reports.taxes') }}" class="btn btn-secondary">Reset</a>
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
                    <h6 class="card-title">Total Medical Benefits</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('medical'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Social Security</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('security'), 2) }}</h3>
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
                    <h6 class="card-title">Total Tax Burden</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('medical') + $payrolls->sum('security') + $payrolls->sum('edu_levy'), 2) }}</h3>
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
                                    <th>Age</th>
                                    <th>Gross Pay</th>
                                    <th>Medical Benefits</th>
                                    <th>Social Security</th>
                                    <th>Education Levy</th>
                                    <th>Total Deductions</th>
                                    <th>Net Pay</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                <tr>
                                    <td>{{ $payroll->user->name }}</td>
                                    <td>{{ $payroll->user->department->name ?? 'N/A' }}</td>
                                    <td>{{ Carbon\Carbon::parse($payroll->user->employeeProfile->dob)->age }}</td>
                                    <td>${{ number_format($payroll->gross, 2) }}</td>
                                    <td>${{ number_format($payroll->medical, 2) }}</td>
                                    <td>${{ number_format($payroll->security, 2) }}</td>
                                    <td>${{ number_format($payroll->edu_levy, 2) }}</td>
                                    <td>${{ number_format($payroll->medical + $payroll->security + $payroll->edu_levy, 2) }}</td>
                                    <td>${{ number_format($payroll->net_pay, 2) }}</td>
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