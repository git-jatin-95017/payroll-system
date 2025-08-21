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
                    <a href="{{ route('reports.download-leave-excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> Excel
                    </a>
                    <a href="{{ route('reports.download-leave-pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
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
                    <form action="{{ route('reports.leave') }}" method="GET" class="row g-3">
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
                        <div class="col-md-3">
                            <label class="form-label">Leave Policy</label>
                            <select class="form-select" name="leave_type_id">
                                <option value="">All Leave Policies</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('reports.leave') }}" class="btn btn-secondary">Reset</a>
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
                    <h6 class="card-title">Total Leave Requests</h6>
                    <h3 class="mb-0">{{ $leaveRequests->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Paid Leave Days</h6>
                    <h3 class="mb-0">{{ $leaveRequests->where('status', 1)->sum('days') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Unpaid Leave Days</h6>
                    <h3 class="mb-0">{{ $leaveRequests->where('status', 0)->sum('days') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Employees</h6>
                    <h3 class="mb-0">{{ $leaveRequests->unique('user_id')->count() }}</h3>
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
                                    <th>Requests</th>
                                    <th>Status</th>
                                    <th>Paid/Unpaid</th>
                                    <th>Leave Policy</th>
                                    <th>Total Used</th>
                                    <th>Total Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveRequests as $request)
                                    <tr>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->pay_period }}</td>
                                        <td>{{ $request->requests }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->leave_status == 'approved' ? 'success' : ($request->leave_status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($request->leave_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->leave_type }}</td>
                                        <td>{{ $request->leaveType->name }}</td>
                                        <td>{{ $request->total_used }} hrs</td>
                                        <td>{{ $request->leave_balance }} hrs</td>
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