@extends('layouts.app')

@section('content')
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Import GS Codes</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Import</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		@error('file')
			<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
		@enderror
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6">
				    <div class="card card-primary">
				        <div class="card-header">
				            <h3 class="card-title">Sample File Template</h3>
				        </div>
				       	<div class="card-body">
				       		<div id="heading-links" class="card-header">
				       			<p class="font-light">Click here <a href="{{ route('download-sample', 'G_S_Codes_Sample_Data.xlsx')}}">Download Sample File - (G_S_Codes_Sample_Data.xlsx)</a> to download the template that should be used to upload GS Codes into the system. Once you've downloaded the template, open MS Excel, add your GS Codes in and then use the form to the right to load them into the system. </p>
				       		</div>
				       	</div>
				       	 <div class="card-footer">
			                &nbsp;
			            </div>
				    </div>
				</div>
				<div class="col-sm-6">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Upload Sample File</h3>
						</div>
						<form id="excel-csv-import-form" method="POST"  action="{{ route('gs-code.store') }}" accept-charset="utf-8" enctype="multipart/form-data">
							@csrf
							<div class="card-body">
								<div class="form-group col-sm-6">									
									<label for="exampleInputFile">File</label>
									<div class="input-group">
										<div class="custom-file">
											<input name="file" type="file" class="custom-file-input" id="exampleInputFile">
											<label class="custom-file-label" for="exampleInputFile">Choose file</label>
										</div>
									</div>
									<small class="text-danger"><i>Note: Please upload only xls format.</i></small>
								</div>
							</div>
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection