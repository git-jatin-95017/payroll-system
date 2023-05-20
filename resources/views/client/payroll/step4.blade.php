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

            <li class="breadcrumb-item active">Run Payroll</li>
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
                        <h3 class="text-themecolor">Run Payroll</h3>
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
                            <p>$31404.15</p>
                        </div>
                        <div class="dabit-data">
                            <span>Dabit Amount</span>
                            <p>$2455.12</p>
                        </div>
                        <div class="dabit-data">
                            <span>Dabit Date</span>
                            <p>$Wed June, 2022</p>
                        </div>
                        <div class="dabit-data">
                            <span>Employee Dabit Date</span>
                            <p>Fri, June 2022</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary mr-2">Submit Payroll</button>
                        <button class="btn btn-default ml-2">Go back</button>
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
                                    What gets taxed and debited
                                </h2>
                              </div>
                          
                              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    data first tab hree
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-head" id="headingTwo">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What your employees worked & take home
                                </h2>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    data second tab hree
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-head" id="headingThree">
                                <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What your company Pays
                                </h2>
                              </div>
                              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table table-sm table-pay-data">
                                        <thead>
                                          <tr>
                                            <th scope="col">Employees</th>
                                            <th scope="col">Gross Pay</th>
                                            <th scope="col">Reimbursements</th>
                                            <th scope="col">Company Taxes</th>
                                            <th scope="col">Company Benifits</th>
                                            <th scope="col">Subtotol</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td >Mark</td>
                                            <td>$2345.00</td>
                                            <td>$0.00</td>
                                            <td>$179.00</td>
                                            <td>$93.35</td>
                                            <td>$12619.49</td>
                                          </tr>
                                          <tr>
                                            <td >Mark</td>
                                            <td>$2345.00</td>
                                            <td>$0.00</td>
                                            <td>$179.00</td>
                                            <td>$93.35</td>
                                            <td>$12619.49</td>
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
			"blueberry",
			"grape",
			"apple",
		],
		datasets: [{
		data: [15, 20, 30],
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