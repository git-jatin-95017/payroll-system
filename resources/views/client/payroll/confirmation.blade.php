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
                <div class="col">
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
                            Hooray! Your payroll is finished processing.
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
                            <a href="{{ route('store.Step2', [
                                'start_date' => Request::query('start_date'),
                                'end_date' => Request::query('end_date'),
                                'appoval_number'=> Request::query('appoval_number')
                            ]) }}" class="btn btn-primary mr-2">Go Back</a>
                        </form>                       
                    </div>
                    
                </div>
             
                <div class="mychart pl-4f">
                    <canvas id="myChart" width="600" height="600"></canvas>
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
                                            <th scope="col">Dbl OT</th>
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
                                                    <td>
                                                        ${{number_format($row->medical+$row->security+$row->edu_levy+$row->security_employer, 2)}}
                                                    </td>
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
                                                    <td>
                                                        <h5><b>${{number_format($total, 2)}}</b></h5>
                                                        <h5><b>Total Payroll</b></h5>
                                                    </td>
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<script>
    // setup 
    const data = {
      labels: ['Employee Pay', 'Taxes', 'Deductions'],
      datasets: [{
        label: 'Weekly Sales',
        data: [18, 12, 6],
        backgroundColor: [
        "#418f26",
			"#d7541b",
			"#f29314",
        ],
        borderColor: [
        "#418f26",
			"#d7541b",
			"#f29314",
        ],
        borderWidth: 0
      }]
    };

    const centerTextDoughnut ={
   id: 'centerTextDoughnut',
      afterDatasetsDraw(chart, args, pluginOptions){
      const{ctx} = chart;
      ctx.font = 'bold 12px sans-serif'
      const text= 'Total Gross Pay: 20';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'Middle';
      const textWidth = ctx.measureText(text).width;
      const x = chart.getDatasetMeta(0).data[0].x
      const y = chart.getDatasetMeta(0).data[0].y
      ctx.fillText(text, x, y);
   }
  }
    // config 
    const config = {
      type: 'doughnut',
      data,
      options: {
               responsive: true,
               cutout: '85%',
               plugins: {
                  legend: {
                     display: true,
                     position: 'left',
                     align: 'center',
                     labels: {
                        color: 'black',
                        font: {
                           weight: 'normal'
                        },
                     }
                  }
               }
            },
            plugins: [centerTextDoughnut],
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );

    // Instantly assign Chart.js version
    const chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;
    </script>
<style>
    .mychart {
    width: 350px;
}
</style>
@endsection

