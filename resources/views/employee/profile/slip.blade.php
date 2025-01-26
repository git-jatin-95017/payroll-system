@extends('layouts.new_layout')

@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Payslips</h3>
			<p class="mb-0">Track and manage your payslips here</p>
		</div>
    </div>
    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <!-- <form method="GET" action="{{ route('notice.index') }}" class="d-flex gap-3 align-items-center justify-content-between mb-4">
            <div class="search-container">
                <div class="d-flex align-items-center gap-3">
                    <p class="mb-0 position-relative search-input-container">
                        <x-heroicon-o-magnifying-glass class="search-icon" />
                        <input type="search" class="form-control" name="search" placeholder="Type here" value="{{request()->search ?? ''}}">
                    </p>
                    <button type="submit" class="btn search-btn">
                        <x-bx-filter class="w-20 h-20"/>
                        Search
                    </button>
                </div>
            </div>
        </form> -->
   </div>
   @if (session('message'))
   <div>
      <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         {{ session('message') }}
      </div>
   </div>
   @elseif (session('error'))
   <div class="col-md-12">
      <div class="alert alert-danger alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         {{ session('error') }}
      </div>
   </div>
   @endif
   <div class="bg-white p-4">
        <div class="table-responsive">
            <table class="table db-custom-table">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Gross Pay</th>
                        <!-- <th scope="col">Regular hours</th> -->
                        <!-- <th scope="col">OT</th> -->
                        <!-- <th scope="col">Dbl OT</th> -->
                        <!-- <th scope="col">Holiday pay</th> -->
                        <th scope="col">Medical benefits</th>
                        <th scope="col">Social Security</th>
                        <th scope="col">Education levy</th>
                        <th scope="col">Additions</th>
                        <th scope="col">Deductions</th>
                        <!-- <th scope="col">Paid time off</th> -->
                        <th scope="col">Employee Pay</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php  
                        $totalEmployeePay =0; 
                        $totalTaxes =0; 
                        $totalDeductions =0; 
                        $grossFinal =0;
                        $nothingAdditionTonetPayTotal = 0 ; 

                        $medical_benefits = $social_security = $education_lvey = $social_security_employer = 0;
                    @endphp
                    @foreach($data as $k => $row)
                        <?php
                            $medical_less_60_amt = $row->medical_less_60 ?? $settings->medical_less_60;
                            $medical_gre_60_amt = $row->medical_gre_60 ?? $settings->medical_gre_60;
                            $social_security_amt = $row->social_security ?? $settings->social_security;
                            $social_security_employer_amt = $row->social_security_employer ?? $settings->social_security_employer;
                            $education_levy_amt = $row->education_levy ?? $settings->education_levy;
                            $education_levy_amt_5 = $row->education_levy ?? $settings->education_levy_5;

                            $gross =0;
                            $employeePay =0;
                            $deductions = 0;
                            $earnings = 0;
                            // $paidTimeOff = 0;
                            // $reimbursement = $row->reimbursement;
                            $nothingAdditionTonetPay = 0;

                            if (count($row->additionalEarnings) > 0) {
                                foreach($row->additionalEarnings as $key => $val) {
                                    if($val->payhead->pay_type =='earnings') {
                                        $earnings += $val->amount;
                                    }

                                    if($val->payhead->pay_type =='deductions') {
                                        $deductions += $val->amount;
                                    }

                                    if($val->payhead->pay_type =='nothing') {
                                        $nothingAdditionTonetPay += $val->amount;
                                    }
                                }
                            }

                            // if (count($row->additionalPaids) > 0){
                            //     foreach($row->additionalPaids as $key => $val) {
                            //         $paidTimeOff += $val->amount;                                            
                            //     }
                            // }

                            // $regHrs = $row->user->employeeProfile->pay_rate * $row->total_hours;

                            $gross = $row->gross + $row->paid_time_off;

                            $pay_type = $row->user->employeeProfile->pay_type;
                            $diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
                            $dob = $diff->format('%y');
                            $days = $row->total_hours;
                            
                            if ($pay_type == 'hourly' || $pay_type == 'weekly') {
                                if ($dob <= 60) {
                                    $medical_benefits = ($gross * $medical_less_60_amt) / 100;
                                } else if ($dob > 60 && $dob <=79 ) {
                                    $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
                                } else if ($dob > 70 ) {
                                    $medical_benefits = 0;
                                }

                                $social_security = ( $gross>1500 ? ((1500*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 );  
                                $social_security_employer = ( $gross>1500 ? ((1500*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 );  
                                $education_lvey = ($gross<=125?0:($gross>1154?( ((1154-125)*$education_levy_amt) / 100)+( (($gross-1154)*$education_levy_amt_5) / 100 ):( (($gross-125)*$education_levy_amt) /100)));
                                $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
                                $net_pay = $gross - $mbse_deductions;
                            } else if ($pay_type == 'bi-weekly') {
                                //$medical_benefits = ($gross * 3.5) / 100;
                                if ($dob <= 60) {
                                    $medical_benefits = ($gross * $medical_less_60_amt) / 100;
                                } else if ($dob > 60 && $dob <=79 ) {
                                    $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
                                } else if ($dob > 70 ) {
                                    $medical_benefits = 0;
                                }

                                if ($days <= 7) {
                                    $social_security = ( $gross>3000 ? ((3000*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
                                    $social_security_employer = ( $gross>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
                                } else {
                                    $social_security = ( $gross>3000 ? ((3000*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
                                    $social_security_employer = ( $gross>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
                                }
                                $education_lvey = ($gross<=250?0:($gross>2308?(((2308-250)*$education_levy_amt)/100)+((($gross-2308)*$education_levy_amt_5)/100):((($gross-250)*$education_levy_amt)/100)));
                                $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
                                $net_pay = $gross - $mbse_deductions;
                                if ($days <= 7) {
                                } else {
                                    $net_pay = 2 * $net_pay;                
                                }
                            } else if ($pay_type == 'semi-monthly') {
                                if ($dob <= 60) {
                                    $medical_benefits = ($gross * $medical_less_60_amt) / 100;
                                } else if ($dob > 60 && $dob <=79 ) {
                                    $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
                                } else if ($dob > 70 ) {
                                    $medical_benefits = 0;
                                }
                                $social_security = ( $gross>3000 ? ((3000*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
                                $social_security_employer = ( $gross>3000 ? ((3000*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
                                $education_lvey = ($gross<=125?0:($gross>2500?(((2500-270.84)*$education_levy_amt)/100)+((($gross-2500)*$education_levy_amt_5)/100):((($gross-270.84)*$education_levy_amt)/100)));
                                $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
                                $net_pay = $gross - $mbse_deductions;
                            } else if ($pay_type == 'monthly') {
                                if ($dob <= 60) {
                                    $medical_benefits = ($gross * $medical_less_60_amt) / 100;
                                } else if ($dob > 60 && $dob <=79 ) {
                                    $medical_benefits = ($gross * $medical_gre_60_amt) / 100;
                                } else if ($dob > 70 ) {
                                    $medical_benefits = 0;
                                }
                                $social_security = ( $gross>6500 ? ((6500*$social_security_amt) / 100) : ($gross*$social_security_amt) / 100 ); 
                                $social_security_employer = ( $gross>6500 ? ((6500*$social_security_employer_amt) / 100) : ($gross*$social_security_employer_amt) / 100 ); 
                                $education_lvey = ($gross<=125?0:($gross>5000?(((5000-541.67)*$education_levy_amt)/100)+((($gross-5000)*$education_levy_amt_5)/100):((($gross-541.67)*$education_levy_amt)/100)));
                                $mbse_deductions = $medical_benefits + $social_security + $education_lvey;
                                $net_pay = $gross - $mbse_deductions;
                            }
                            // $gross += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off); commented

                            $grossFinal += $gross;
                            // $grossFinal += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off);

                            $employeePay = $gross- ($mbse_deductions) + ($nothingAdditionTonetPay) - $deductions;

                            $totalEmployeePay += $employeePay;
                            // $totalTaxes += ($row->medical +$row->security + $row->edu_levy);
                            $totalDeductions += $deductions;
                            // $totalAdditions += $earnings;
                            $nothingAdditionTonetPayTotal += $nothingAdditionTonetPay;

                            if ($gross == 0) {
                                continue;
                            }
                        ?>
                        
                        <tr>
                            <td>{{ $row->start_date }}</td>
                            <td>${{number_format($gross, 2)}}</td> <?php //$gross; commented?>
                            <td>${{number_format($medical_benefits, 2)}}</td>
                            <td>${{number_format($social_security, 2)}}</td>
                            <td>${{number_format($education_lvey, 2)}}</td>
                            <td>${{number_format($nothingAdditionTonetPay, 2)}}</td>
                            <td>${{number_format($deductions, 2)}}</td>
                            <?php
                            /*
                            <td>{{$row->total_hours}}</td>
                            <td>{{$row->overtime_hrs}}</td>
                            <td>{{$row->doubl_overtime_hrs}}</td>
                            <td>${{number_format($row->holiday_pay, 2)}}</td>
                            */
                            ?>                                                
                            <td>${{number_format($employeePay, 2)}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn action-dropdown-toggle dropdown-toggle" type="button" id="dropdownMenuButton{$k}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-bx-dots-horizontal-rounded class="w-20 h-20" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{$k}">
                                        <li>
                                            <a href="{{ route('empdownload.pdf') }}?id={{$row->id}}" class="dropdown-item">
                                                <x-bx-map-alt class="w-16 h-16" /> Download
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>                                                 
                        </tr>                
                    @endforeach
     
                    <!-- <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>                                                    
                        <td>
                            <span style="color: #000 !important;font-weight: 700 !important;">${{number_format($totalEmployeePay, 2)}}</span><br>
                            <small style="color: #000 !important;font-weight: 600 !important;">Total Employee Pay</small>
                        </td>
                    </tr>        -->
                </tbody>
            </table>
        </div>
   </div>
</div>
@endsection
@push('page_scripts')
	<script>
		function toggleInput(obj, index) {
			console.log(obj);
			$('#input-'+index).attr('readonly', false);
		}

		function saveData(obj, index) {
			if ($(obj).val() != '' || $(obj).val() != null) {
				$.ajax({
					url: "{{ route('save.name.payroll') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}", 
						key: $(obj).data('id'), 
						name: $(obj).val(), 
					},
					dataType: 'JSON',
					success: function (data) {
						// alert('Record Saved Successfully.');
						$('#input-'+index).attr('readonly', true);
					}
				});
			}
		}
	</script>
@endpush