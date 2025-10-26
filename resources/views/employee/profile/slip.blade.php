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
                    @endphp
                    @foreach($calculatedData as $k => $item)
                        @php
                            $row = $item['row'];
                            $amounts = $item['amounts'];
                            
                            // Use pre-calculated values from trait
                            $gross = $amounts['gross'];
                            $medical_benefits = $amounts['medical_benefits'];
                            $social_security = $amounts['social_security'];
                            $education_lvey = $amounts['education_levy'];
                            $nothingAdditionTonetPay = $amounts['nothing_addition'];
                            $deductions = $amounts['deductions'];
                            $employeePay = $amounts['employee_pay'];
                            
                            // Accumulate totals
                            $grossFinal += $gross;
                            $totalEmployeePay += $employeePay;
                            $totalDeductions += $deductions;
                            $nothingAdditionTonetPayTotal += $nothingAdditionTonetPay;
                            
                            if ($gross == 0) {
                                continue;
                            }
                        @endphp
                        
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
                                            <a href="{{ route('empdownload.pdf') }}?id={{$row->id}}&no_dds_download=1" class="dropdown-item">
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