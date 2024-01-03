<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>index</title>
	<style>
		  body{
			font-family: Arial, Helvetica, sans-serif;
			font-size: 13px;
			-webkit-print-color-adjust: exact;
			color: #6e6e6e;
			}
		@page {
			margin:10px 5px;
			size: letter; /*or width then height 150mm 50mm*/
		}
		@page wide {
		 size: a4 landscape;
		}
	</style>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
@php  
	$bankData = []; 
	$bankHTML = '';
	$totalEmployeePay =0; 
	$totalTaxes =0; 
	$totalDeductions =0; 
	$grossFinal =0;
	$nothingAdditionTonetPayTotal = 0 ; 

	$medical_benefits = $social_security = $education_lvey = $social_security_employer = 0;
@endphp
@foreach($data as $row)
	<?php
		$bankName = ucfirst($row->user->paymentProfile->bank_name);

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

		$gross = $row->gross + $row->paid_time_off;

		$pay_type = $row->user->employeeProfile->pay_type;
		$diff = date_diff(date_create($row->user->employeeProfile->dob), date_create(date("Y-m-d")));
		$dob = $diff->format('%y');
		$days = $row->total_hours;
	   
		if ($pay_type == 'hourly' || $pay_type == 'weekly') {
			if ($dob <= 60) {
				$medical_benefits = ($gross * 3.5) / 100;
			} else if ($dob > 60 && $dob <=79 ) {
				$medical_benefits = ($gross * 2.5) / 100;
			} else if ($dob > 70 ) {
				$medical_benefits = 0;
			}

			$social_security = ( $gross>1500 ? ((1500*6.5) / 100) : ($gross*6.5) / 100 );  
			$social_security_employer = ( $gross>1500 ? ((1500*8.5) / 100) : ($gross*8.5) / 100 );  
			$education_lvey = ($gross<=125?0:($gross>1154?( ((1154-125)*2.5) / 100)+( (($gross-1154)*5) / 100 ):( (($gross-125)*2.5) /100)));
			$mbse_deductions = $medical_benefits + $social_security + $education_lvey;
			$net_pay = $gross - $mbse_deductions;
		} else if ($pay_type == 'bi-weekly') {
			//$medical_benefits = ($gross * 3.5) / 100;
			if ($dob <= 60) {
				$medical_benefits = ($gross * 3.5) / 100;
			} else if ($dob > 60 && $dob <=79 ) {
				$medical_benefits = ($gross * 2.5) / 100;
			} else if ($dob > 70 ) {
				$medical_benefits = 0;
			}

			if ($days <= 7) {
				$social_security = ( $gross>3000 ? ((3000*6.5) / 100) : ($gross*6.5) / 100 ); 
				$social_security_employer = ( $gross>3000 ? ((3000*8.5) / 100) : ($gross*8.5) / 100 ); 
			} else {
				$social_security = ( $gross>3000 ? ((3000*6.5) / 100) : ($gross*6.5) / 100 ); 
				$social_security_employer = ( $gross>3000 ? ((3000*8.5) / 100) : ($gross*8.5) / 100 ); 
			}
			$education_lvey = ($gross<=250?0:($gross>2308?(((2308-250)*2.5)/100)+((($gross-2308)*5)/100):((($gross-250)*2.5)/100)));
			$mbse_deductions = $medical_benefits + $social_security + $education_lvey;
			$net_pay = $gross - $mbse_deductions;
			if ($days <= 7) {
			} else {
				$net_pay = 2 * $net_pay;                
			}
		} else if ($pay_type == 'semi-monthly') {
			if ($dob <= 60) {
				$medical_benefits = ($gross * 3.5) / 100;
			} else if ($dob > 60 && $dob <=79 ) {
				$medical_benefits = ($gross * 2.5) / 100;
			} else if ($dob > 70 ) {
				$medical_benefits = 0;
			}
			$social_security = ( $gross>3000 ? ((3000*6.5) / 100) : ($gross*6.5) / 100 ); 
			$social_security_employer = ( $gross>3000 ? ((3000*8.5) / 100) : ($gross*8.5) / 100 ); 
			$education_lvey = ($gross<=125?0:($gross>2500?(((2500-270.84)*2.5)/100)+((($gross-2500)*5)/100):((($gross-270.84)*2.5)/100)));
			$mbse_deductions = $medical_benefits + $social_security + $education_lvey;
			$net_pay = $gross - $mbse_deductions;
		} else if ($pay_type == 'monthly') {
			if ($dob <= 60) {
				$medical_benefits = ($gross * 3.5) / 100;
			} else if ($dob > 60 && $dob <=79 ) {
				$medical_benefits = ($gross * 2.5) / 100;
			} else if ($dob > 70 ) {
				$medical_benefits = 0;
			}
			$social_security = ( $gross>6500 ? ((6500*6.5) / 100) : ($gross*6.5) / 100 ); 
			$social_security_employer = ( $gross>6500 ? ((6500*8.5) / 100) : ($gross*8.5) / 100 ); 
			$education_lvey = ($gross<=125?0:($gross>5000?(((5000-541.67)*2.5)/100)+((($gross-5000)*5)/100):((($gross-541.67)*2.5)/100)));
			$mbse_deductions = $medical_benefits + $social_security + $education_lvey;
			$net_pay = $gross - $mbse_deductions;
		}
		// $gross += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off); commented

		$grossFinal += $gross;
		// $grossFinal += ($regHrs + $row->overtime_hrs + $row->doubl_overtime_hrs + $row->holiday_pay + ($earnings + $nothingAdditionTonetPay) + $row->paid_time_off);

		$employeePay = $gross- ($mbse_deductions) + ($nothingAdditionTonetPay) - $deductions;

		$totalEmployeePay += $employeePay;
		// $totalTaxes += ($row->medical +$row->security + $row->edu_levy);
		// $totalDeductions += $deductions;
		// $totalAdditions += $earnings;
		// $nothingAdditionTonetPayTotal += $nothingAdditionTonetPay;
		
		// Group data by bank name
        if (!isset($bankData[$bankName])) {
            $bankData[$bankName] = [
                'count' => 1,
                'totalAmount' => $employeePay,
            ];
        } else {
            $bankData[$bankName]['count']++;
            $bankData[$bankName]['totalAmount'] += $employeePay;
        }

		$bankHTML.= '<tr>
		    <td style="padding: 3px 5px; border-right: 1px solid #ddd;">' . ucfirst($row->user->paymentProfile->bank_name) . '</td>
		    <td style="padding: 3px 5px; border-right: 1px solid #ddd;">' . ucfirst($row->user->employeeProfile->first_name) . '</td>
		    <td style="padding: 3px 5px; border-right: 1px solid #ddd;">' . ucfirst($row->user->employeeProfile->last_name) . '</td>
		    <td style="padding: 3px 5px; border-right: 1px solid #ddd;">' . ucfirst($row->user->paymentProfile->account_number) . '</td>
		    <td style="padding: 3px 5px; border-right: 1px solid #ddd;">' . ucfirst($row->user->paymentProfile->account_type) . '</td>
		    <td style="padding: 3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">$' . number_format($employeePay, 2) . '</td>
		</tr>';                 
	?>
@endforeach
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px;">

	<tr>
		<td style="padding:3px 5px; margin-bottom: 25px;">
			<strong style="font-size: 13px; color: #000;">Direct Deposit List </strong>
		</td>
	</tr>
	<tr>
		<td style="padding:3px 5px;">&nbsp;</td>
	</tr>
	<tr>
		<td style="padding:3px 5px;">
			<span style="font-size: 14px; color: #000;">{{ strtoupper(auth()->user()->name) }}</span>
		</td>
	</tr><br>
	<tr>
		<td>
			<table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
				<!-- <tr>
					<td style="padding:3px 5px;">
						<strong style="font-size: 14px; color: #000;">Date of email: {{ date('m/d/Y') }}</strong>
					</td>
				</tr> -->
				<tr>
					<td style="padding:3px 5px;">
						<strong style="font-size: 14px; color: #000;">{{ ucfirst(auth()->user()->paymentProfile->bank_name) }}</strong>
					</td>
				</tr>

				<tr>
					<td style="padding:3px 5px;">
						<strong style="font-size: 14px; color: #000;">{{ ucfirst(auth()->user()->companyProfile->address) }}</strong>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<p style="text-align: center;">RE: Payroll Direct Deposit Request</p>
<p>Dear Sir/Madam</p>
<p>We write to request your assistance in making payroll direct deposits to our employee. Please
see attached list of related employee names and bank account #'s listed. Therefore, kindly
accept this letter as authorization to debit our account# {{ auth()->user()->paymentProfile->account_number }} for
${{number_format($totalEmployeePay, 2)}} (Total Employee Amount) and make the necessary transfers as detailed in the
afprmentioned list.</p>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
	<tbody>
		<td>
			<table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
				<thead style="border-bottom: 2px solid #58a8a4;">
					<tr>
						<th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="2">Direct Deposit Summary</th>
					</tr>
				</thead>
				<thead style="border-bottom: 1px solid #ddd;">
					<tr>
						<!-- <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th> -->
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Bank Name</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Deposit Total</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Deposit Amount</th>
					</tr>
					<tbody>
    					@if(count($bankData) > 0)
    					@foreach($bankData as $k => $bank)
						<tr>						
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;">{{$k}}</td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;">{{$bank['count']}}</td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;">{{number_format($bank['totalAmount'], 2)}}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</thead>
			</table>
		</td>       
	</tbody>
</table>
<table style="width: 100%; border-collapse: collapse; table-layout: fixed; text-align: left; vertical-align: middle; margin-bottom: 25px;">
	<tbody>
		<td>
			<table style="width: 100%; border-collapse:collapse; table-layout: fixed;">
				<thead style="border-bottom: 2px solid #58a8a4;">
					<tr>
						<th style="padding:3px 5px; color: #000; font-size: 14px;" colspan="2">Direct Deposit List</th>
					</tr>
				</thead>
				<thead style="border-bottom: 1px solid #ddd;">
					<tr>
						<!-- <th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">Description</th> -->
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">BANK NAME</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">FIRST NAME</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">LAST NAME</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">BANK ACCOUNT NUMBER</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; color: #000;">AC TYPE</th>
						<th style="padding:3px 5px; border-right: 1px solid #ddd; text-align: right; color: #000;">NET AMOUNT</th>                        
					</tr>
					<tbody>
    					{!! $bankHTML !!}
						<tr>						
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;">Total</td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;"></td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;"></td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;"></td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; color: #000;"></td>
							<td style="padding:3px 5px; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; border-right: 1px solid #ddd; text-align: right; color: #000;">${{number_format($totalEmployeePay, 2)}}</td>
						</tr>
					</tbody>
				</thead>
			</table>
		</td>       
	</tbody>
</table>

<p>We thank you for your assistance.</p>
<p>Authorized By: ____________________</p>
<p>Position: ____________________</p>
<p>Signiture: ____________________</p>

</body>
</html>