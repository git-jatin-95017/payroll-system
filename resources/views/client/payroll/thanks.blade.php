@extends('layouts.new_layout')

@section('content')
<div class="img-load">
	<div>
		<img src="{{asset('img/new-load.gif')}}" alt="logo" >
	</div>
</div>
<section class="content">
	<div class="bg-white w-100 border-radius-15 p-4">		
		<div class="tab-content" id="myTabContent">
			@if (session('message'))
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('message') }}
						</div>
					</div>
				</div>
			@elseif (session('error'))
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{{ session('error') }}
						</div>
					</div>
				</div>
			@endif
			<div class="row">
				<div class="col-sm-12 mt-4 pt-4">	
					<div class="confirm-container">
						<div class="text-center">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="60" height="60">
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
							</svg>
							<h3>Calculating Payroll</h3>
							<p class="text-center font-bold">Relax while we compute your payment. No need for a calculator, you’re in good hands.</p>
						</div>
					</div>						
				</div>
			</div>
			</div>
		</div>
	</div>
</section>
<a class="d-none" href="{{route('payroll.confirmation', ['start_date'=>$start_date,'end_date' =>$end_date,'appoval_number'=> $appoval_number])}}" id="confirm">confirm</a>
@endsection

@push('page_scripts')
	<script>
		window.addEventListener('load', function () {
			setTimeout(function() {
		  		document.getElementById("confirm").click();
		  	}, 2000);
		});
	</script>


<style>

/* .content{
	position: relative;
} */

.img-load {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(0,0,0, 0.4);
	z-index: 100;
}

.img-load div{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}
</style>
@endpush