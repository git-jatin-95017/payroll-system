@extends('layouts.new_layout')
@section('content')

@push('styles')
<style>
.gross-pay-link {
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.gross-pay-link:hover {
    opacity: 0.8;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.5);
}

.modal.show {
    display: block !important;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 1.75rem auto;
    max-width: 500px;
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    padding: 0.75rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

.modal-open {
    overflow: hidden;
}

.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    background: transparent;
    border: 0;
    cursor: pointer;
}

.close:hover {
    opacity: .75;
}
</style>
<style>
    .my-table td {
        border-bottom-width: 0px !important;
    }
    .my-table th, .my-table tr, .my-table td {
        background: none !important;
        border-bottom: none !important;
    }
</style>
@endpush
<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row">
        <div class="col-12">
            <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3>Reports</h3>
                    <!-- <p class="mb-0">What do employees get?</p> -->
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
					<a href="{{ route('payroll.reports.download-report-excel', 'employee-earnings') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i>  Excel
                    </a>
                    <a href="{{ route('payroll.reports.download-report-pdf', 'employee-earnings') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary">
                        <i class="fas fa-download"></i>  PDF
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
        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Pay</h6>
                    <h3 class="mb-0">${{ number_format($payrolls->sum('net_pay'), 2) }}</h3>
                </div>
            </div>
        </div> -->
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
        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Employees</h6>
                    <h3 class="mb-0">{{ $payrolls->unique('user_id')->count() }}</h3>
                </div>
            </div>
        </div> -->
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
                                    <th>Additions</th>
                                    <th>Deductions</th>
                                    <th>Employee Pay</th>
                                </tr>
                            </thead>
                            <tbody>
							@php
							$grosspay =0;
							$medicalbenefits =0;
							$socialsecurity =0;
							$educationlevy =0;
							$additionsClone = 0;
							$deductionsClone = 0;
							$totalemppay = 0;
                                foreach($payrolls as $payroll) {
									$grosspay += ($payroll->gross + $payroll->paid_time_off);
									// Note: medicalbenefits, socialsecurity, educationlevy totals will be calculated in the loop below
									$add =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2);
									$ded =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2);
									$nothing =  number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2);
									$additionsClone += $add;
									$deductionsClone += $ded;
									$totalemppay += $payroll->employee_pay; 
							@endphp		
									<tr>
										<td>{{ $payroll->user->name }}</td>
										<td>{{ date('M d, Y', strtotime($payroll->start_date)) }} - {{ date('M d, Y', strtotime($payroll->end_date)) }}</td>
										<td>
                                            <a href="#" class="gross-pay-link" data-toggle="modal" data-target="#grossPayModal{{ $payroll->id }}" style="text-decoration: none;">
                                                ${{ number_format($payroll->gross + $payroll->paid_time_off, 2) }}
                                            </a>
                                            
                                            <!-- Gross Pay Details Modal -->
                                            <div class="modal fade" id="grossPayModal{{ $payroll->id }}" tabindex="-1" role="dialog" aria-labelledby="grossPayModalLabel{{ $payroll->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="grossPayModalLabel{{ $payroll->id }}">
                                                                Gross Pay Details
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            
                                                            <table class="table my-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Description</th>
                                                                            <th class="text-end">Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @if($payroll->reg_hrs_temp > 0)
                                                                        <tr>
                                                                            <td>Regular Hours</td>
                                                                            <td class="text-end">${{ number_format($payroll->reg_hrs_temp, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @if($payroll->overtime_hrs > 0)
                                                                    @php
                                                                        $pay_type = $payroll->user->employeeProfile->pay_type;
                                                                        $rate_per_hour = $payroll->user->employeeProfile->pay_rate;
                                                                        $PDT = 0;
                                                                        
                                                                        if ($pay_type == 'hourly') {
                                                                            $PDT = $rate_per_hour;
                                                                        } else if ($pay_type == 'weekly') {
                                                                            $PDT = ($rate_per_hour / 40);
                                                                        } else if ($pay_type == 'bi-weekly') {
                                                                            $PDT = (((($rate_per_hour * 26)/52)/40));
                                                                        } else if ($pay_type == 'semi-monthly') {
                                                                            $PDT = (((($rate_per_hour * 24)/52)/40));
                                                                        } else if ($pay_type == 'monthly') {
                                                                            $PDT = (((($rate_per_hour * 12)/52)/40));
                                                                        }
                                                                        
                                                                        $overtime_calc = ($PDT * 1.5) * $payroll->overtime_hrs;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>Overtime</td>
                                                                        <td class="text-end">${{ number_format($overtime_calc, 2) }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    @if($payroll->doubl_overtime_hrs > 0)
                                                                    @php
                                                                        $double_overtime_calc = ($PDT * 2) * $payroll->doubl_overtime_hrs;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>Double Overtime</td>
                                                                        <td class="text-end">${{ number_format($double_overtime_calc, 2) }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    @if($payroll->holiday_pay > 0)
                                                                    @php
                                                                        $holiday_pay_calc = ($PDT * 1.5) * $payroll->holiday_pay;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>Holiday Pay</td>
                                                                        <td class="text-end">${{ number_format($holiday_pay_calc, 2) }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    
                                                                    @foreach($payroll->payheads_list as $payhead)
                                                                        @if($payhead['amount'] > 0 && $payhead['pay_type'] == 'earnings')
                                                                            <tr>
                                                                                <td>{{ ucfirst($payhead['name']) }}</td>
                                                                                <td class="text-end">${{ number_format($payhead['amount'], 2) }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    @if($payroll->paid_time_off > 0)
                                                                        <tr>
                                                                            <td>Paid Time Off ({{   implode(', ', $payroll->additionalPaids->pluck('leaveType.name')->toArray()) }})</td>
                                                                            <td class="text-end">${{ number_format($payroll->paid_time_off, 2) }}</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td class=""><strong>Total Gross Pay</strong></td>
                                                                        <td class="text-end"><strong>${{ number_format($payroll->gross + $payroll->paid_time_off, 2) }}</strong></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
										@php
											// Use calculated values from the trait
											$medical_benefits = $payroll->calculated_medical;
											$social_security = $payroll->calculated_social_security;
											$education_lvey = $payroll->calculated_education_levy;
											
											// Update totals
											$medicalbenefits += $medical_benefits;
											$socialsecurity += $social_security;
											$educationlevy += $education_lvey;
										@endphp
										<td>${{ number_format($medical_benefits, 2) }}</td>
										<td>${{ number_format($social_security, 2) }}</td>
										<td>${{ number_format($education_lvey, 2) }}</td>
										<td>
                                            <a href="#" class="additions-link" data-toggle="modal" data-target="#additionsModal{{ $payroll->id }}" style="text-decoration: none;">
                                                ${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2) }}
                                            </a>
                                            
                                            <!-- Additions Details Modal -->
                                            <div class="modal fade" id="additionsModal{{ $payroll->id }}" tabindex="-1" role="dialog" aria-labelledby="additionsModalLabel{{ $payroll->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="additionsModalLabel{{ $payroll->id }}">
                                                                Additions Details
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table my-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th class="text-end">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                    $additions = $payroll->additionalEarnings->where('payhead.pay_type', 'nothing');
                                                                @endphp
                                                                @if($additions->count() > 0)
                                                                    @foreach($additions as $addition)
                                                                        @if($addition->amount > 0 && $addition->payhead->pay_type == 'nothing')
                                                                            <tr>
                                                                                <td>{{ $addition->payhead->name ?? 'Addition' }}</td>
                                                                                <td class="text-end">${{ number_format($addition->amount, 2) }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td class="text-end">No additions for this period</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td class=""><strong>Total Additions</strong></td>
                                                                    <td class="text-end"><strong>${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'nothing')->sum('amount'), 2) }}</strong></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="cursor: pointer;">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
										<td>
                                            <a href="#" class="deductions-link" data-toggle="modal" data-target="#deductionsModal{{ $payroll->id }}" style="text-decoration: none;">
                                                ${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2) }}
                                            </a>
                                            
                                            <!-- Deductions Details Modal -->
                                            <div class="modal fade" id="deductionsModal{{ $payroll->id }}" tabindex="-1" role="dialog" aria-labelledby="deductionsModalLabel{{ $payroll->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deductionsModalLabel{{ $payroll->id }}">
                                                                Deductions Details
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table my-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th class="text-end">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                    $deductions = $payroll->additionalEarnings->where('payhead.pay_type', 'deductions');
                                                                @endphp
                                                                @if($deductions->count() > 0)
                                                                    @foreach($deductions as $deduction)
                                                                        @if($deduction->amount > 0 && $deduction->payhead->pay_type == 'deductions')
                                                                            <tr>
                                                                                <td>{{ $deduction->payhead->name ?? 'Deduction' }}</td>
                                                                                <td class="text-end">${{ number_format($deduction->amount, 2) }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="2" class="text-end">No deductions for this period</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td class=""><strong>Total Deductions</strong></td>
                                                                    <td class="text-end"><strong>${{ number_format($payroll->additionalEarnings->where('payhead.pay_type', 'deductions')->sum('amount'), 2) }}</strong></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="cursor: pointer;">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
										<td>${{ number_format($payroll->employee_pay, 2) }}</td>
									</tr>
								@php
								}
								@endphp
								<tr>
									<td colspan="2" align="center"><strong>Total</strong></td>
									<td><strong>${{ number_format($grosspay, 2) }}</strong></td>
									<td><strong>${{ number_format($medicalbenefits, 2) }}</strong></td>
									<td><strong>${{ number_format($socialsecurity, 2) }}</strong></td>
									<td><strong>${{ number_format($educationlevy, 2) }}</strong></td>
									<td><strong>${{ number_format(is_numeric($additionsClone) ? $additionsClone : 0, 2) }}</strong></td>
									<td><strong>${{ number_format(is_numeric($deductionsClone) ? $deductionsClone : 0, 2) }}</strong></td>
									<td><strong>${{ number_format($totalemppay, 2) }}</strong></td>
								</tr>
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
    
    // Handle modal clicks (gross pay, additions, deductions)
    document.querySelectorAll('.gross-pay-link, .additions-link, .deductions-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var targetModal = this.getAttribute('data-target');
            var modal = document.querySelector(targetModal);
            if (modal) {
                // Show modal using Bootstrap modal
                if (typeof bootstrap !== 'undefined') {
                    var bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    // Fallback to jQuery modal if Bootstrap JS is not available
                    $(modal).modal('show');
                } else {
                    // Fallback to manual modal display
                    // Create backdrop
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop';
                    backdrop.id = 'backdrop-' + modal.id;
                    document.body.appendChild(backdrop);
                    
                    // Show modal
                    modal.style.display = 'block';
                    modal.classList.add('show');
                    document.body.classList.add('modal-open');
                }
            }
        });
    });
    
    // Handle modal close buttons
    document.querySelectorAll('[data-dismiss="modal"]').forEach(function(button) {
        button.addEventListener('click', function() {
            var modal = this.closest('.modal');
            if (modal) {
                if (typeof bootstrap !== 'undefined') {
                    var bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modal).modal('hide');
                } else {
                    // Remove backdrop
                    var backdrop = document.getElementById('backdrop-' + modal.id);
                    if (backdrop) {
                        backdrop.remove();
                    }
                    
                    // Hide modal
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                }
            }
        });
    });
    
    // Close modal when clicking outside
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                if (typeof bootstrap !== 'undefined') {
                    var bsModal = bootstrap.Modal.getInstance(this);
                    if (bsModal) bsModal.hide();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(this).modal('hide');
                } else {
                    // Remove backdrop
                    var backdrop = document.getElementById('backdrop-' + this.id);
                    if (backdrop) {
                        backdrop.remove();
                    }
                    
                    // Hide modal
                    this.style.display = 'none';
                    this.classList.remove('show');
                    document.body.classList.remove('modal-open');
                }
            }
        });
    });
    
    // Handle backdrop clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            var modalId = e.target.id.replace('backdrop-', '');
            var modal = document.getElementById(modalId);
            if (modal) {
                // Remove backdrop
                e.target.remove();
                
                // Hide modal
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        }
    });
});
</script>
@endpush 