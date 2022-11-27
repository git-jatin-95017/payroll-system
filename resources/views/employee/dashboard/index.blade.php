@extends('layouts.employee')
@section('content')
	<div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-braille" style="color:#1976d2"></i> Dashboard</h3>
                </div>
				
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
			<div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary"><i class="mdi mdi-rocket"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">Total Leaves</h3>
                                        <a href="#" class="text-muted m-b-0">View Details</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
		</div>
	</section>
@endsection
