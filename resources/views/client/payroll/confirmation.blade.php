@extends('layouts.app')

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
            <i class="fa fa-braille" style="color:#1976d2"></i>
            Run Payroll
        </h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
            </li>

            <li class="breadcrumb-item active">Confirm Payroll</li>
			<li class="breadcrumb-item active">/</li>
        </ol>
    </div>
</div>
<section class="content">
	<div class="container-fluid p-4">
        <div class="bg-white p-4">
            <div class="row">
                <div class="col-12">
                    <div class="payroll-heading">
                        <h3 class="text-themecolor">Confirm Payroll</h3>
                    </div>
                </div>
            </div>		
            <div class="row">
                <div class="col-9">
                    <div id="progress-bar" class="mb-4">
                        <h2 class="off-screen">Donation progress indicator</h2>
                        <ol id="progress-steps">
                            <li class="progress-step" style="width: 25%;">
                                <span class="count highlight-index"></span>
                                <span class="description">1 Hours and eraning</span>
                            </li>
                            <li class="progress-step" style="width: 25%;">
                                <span class="count highlight-index"></span>
                                <span class="description">2 Time off</span>
                            </li>
                            <li class="progress-step" style="width: 25%;">
                                <span class="count highlight-index"></span>
                                <span class="description">3 Review and Submit</span>
                            </li>
                        
                            <li class="progress-step" style="width: 25%;">
                            <span class="count"></span>
                            <span class="description">4 Confirmation</span>
                            </li>
                        </ol>
                    </div>
                    <div class="payroll-heading my-5">
                        <h4 class="mb-1 text-themecolor">Review and submit</h4>
                        <p>
                            “Hooray! Your payroll is finished processing. Time to review your summary and submit it your payroll.”
                        </p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="dabit-data">
                            <span>Total Payroll</span>
                            <p>${{number_format($totalPayroll, 2)}}</p>
                        </div>
                        <div class="dabit-data">
                            <span>Taxes</span>
                            <p>${{number_format($taxes, 2)}}</p>
                        </div>
                        <div class="dabit-data">
                            <span>Direct deposits</span>
                            <p>{{$directDeposits}}</p>
                        </div>
                        <div class="dabit-data">
                            <span>Cheques</span>
                            <p>{{$cheques}}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <form action="{{ route('save.confirmation') }}" method="POST">
                            @csrf
                            @foreach($ids as $k => $id)
                                <input type="hidden" name="ids[]" value="{{$id}}">
                            @endforeach
                            <button class="btn btn-primary mr-2" type="submit">Submit Payroll</button>
                        </form>
                        <!-- <button class="btn btn-default ml-2">Go back</button> -->
                        <a href="{{ route('store.Step2', [
                            'start_date' => Request::query('start_date'),
                            'end_date' => Request::query('end_date'),
                            'appoval_number'=> Request::query('appoval_number')
                        ]) }}" class="btn btn-info text-uppercase ml-2 reset_btn">Go Back</a>
                    </div>
                    
                </div>
                <div class="col-3">
                    <canvas id="canvas1" width="200" height="200"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div>
                        <div class="accordion custom-accordion" id="accordionExample">
                            <div class="card">
                              <div class="card-head" id="headingOne">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    What gets taxed?
                                </h2>
                              </div>
                          
                              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                        <thead>
                                          <tr>
                                            <th scope="col">Tax description</th>
                                            <th scope="col">By Employees</th>
                                            <th scope="col">By Employer</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Medical benefits</th>
                                                <td>${{number_format($medicalTotal, 2)}}</td>
                                                <td>${{number_format($medicalTotal, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Social Security</th>
                                                <td>${{number_format($securityTotal, 2)}}</td>
                                                <td>${{number_format($securityEmployerTotal, 2)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Education levy</th>
                                                <td>${{number_format($eduLevytotal, 2)}}</td>
                                                <td>N/A</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>${{number_format($medicalTotal+$securityTotal+$eduLevytotal, 2)}}</td>
                                                <td>${{number_format($medicalTotal+$securityEmployerTotal, 2)}}</td>
                                            </tr>
                                        </tbody>
                                      </table>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-head" id="headingTwo">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What do employees get?
                                </h2>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                        <thead>
                                          <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Regular hours</th>
                                            <th scope="col">OT</th>
                                            <th scope="col">Double OT</th>
                                            <th scope="col">Holiday pay</th>
                                            <th scope="col">Medical benefits</th>
                                            <th scope="col">Social Security</th>
                                            <th scope="col">Education levy</th>
                                            <th scope="col">Net pay</th>
                                            <th scope="col">Additions</th>
                                            <th scope="col">Deductions</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $row)
                                                <?php
                                                    $deductions = 0;
                                                    $earnings = 0;

                                                    if (count($row->additionalEarnings) > 0){
                                                        foreach($row->additionalEarnings as $key => $val) {
                                                            if($val->payhead->pay_type =='earnings') {
                                                                $earnings += $val->amount;
                                                            }

                                                            if($val->payhead->pay_type =='deductions') {
                                                                $deductions += $val->amount;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td>{{ucfirst($row->user->employeeProfile->first_name)}} {{ucfirst($row->user->employeeProfile->last_name)}}</td>
                                                    <td>{{$row->total_hours}}</td>
                                                    <td>{{$row->overtime_hrs}}</td>
                                                    <td>{{$row->doubl_overtime_hrs}}</td>
                                                    <td>${{number_format($row->holiday_pay, 2)}}</td>
                                                    <td>${{number_format($row->medical, 2)}}</td>
                                                    <td>${{number_format($row->security, 2)}}</td>
                                                    <td>${{number_format($row->edu_levy, 2)}}</td>
                                                    <td>${{number_format($row->net_pay, 2)}}</td>
                                                    <td>${{number_format($earnings, 2)}}</td>
                                                    <td>${{number_format($deductions, 2)}}</td>
                                                </tr>                   
                                            @endforeach                      
                                        </tbody>
                                      </table>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-head" id="headingThree">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                   What Employer pays?
                                </h2>
                              </div>
                              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                         <thead>
                                          <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Employee Pay</th>
                                            <th scope="col">Employee Taxes</th>
                                            <th scope="col">Employer taxes</th>
                                            <th scope="col">Subtotal</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <?php $subtotal = 0; $total = 0;?>
                                            @foreach($data as $row)
                                                <?php 
                                                    $subtotal+= ($row->gross+$row->medical+$row->security+$row->edu_levy+$row->security_employer);
                                                    $total+= $subtotal;
                                                ?>
                                                <tr>
                                                    <td>{{ucfirst($row->user->employeeProfile->first_name)}} {{ucfirst($row->user->employeeProfile->last_name)}}</td>
                                                    <td>${{number_format($row->gross, 2)}}</td>
                                                    <td>${{number_format($row->medical+$row->security+$row->edu_levy, 2)}}</td>
                                                    <td>${{number_format($row->security_employer, 2)}}</td>
                                                    <td>${{number_format($subtotal, 2)}}</td>
                                                </tr>  
                                                <?php $subtotal = 0; ?>                 
                                            @endforeach       
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>                                                    
                                                    <td>Total: ${{number_format($total, 2)}}</td>
                                                </tr>               
                                        </tbody>
                                      </table>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
var dataFinal = @json($dataGraph);
// Doughnut Chart 
$(document).ready(function(){
	var options = {
		// legend: false,
		responsive: false
	};
	new Chart($("#canvas1"), {
        cutout : 20,
		type: 'doughnut',
		tooltipFillColor: "rgba(51, 51, 51, 0.55)",
		data: {
		labels: [
			"Employee Pay",
			"Taxes",
			"Deductions",
		],
		datasets: [{
		data: dataFinal,
		backgroundColor: [
			"#3498DB",
			"#9B59B6",
			"#E74C3C",
		],
		hoverBackgroundColor: [
			"#49A9EA",
			"#B370CF",
			"#E95E4F",
		]
		}]
	},
		options: { 
            cutoutPercentage:70,
            responsive: true 
        }
	});           
});
// Doughnut Chart 
</script>