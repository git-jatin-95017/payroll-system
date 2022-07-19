@if ($message = session('success'))
	<div class="alert alert-dismissible fade show alert-success">
		{{ $message }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif

@if ($message = session('error'))
	<div class="alert alert-dismissible fade show alert-danger">
		{{ $message }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif

@if ($message = session('warning'))
	<div class="alert alert-dismissible fade show alert-warning">
		{{ $message }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif

@if ($message = session('info'))
	<div class="alert alert-dismissible fade show alert-info">
		{{ $message }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif

@if ($message = session('status'))
<div class="alert alert-dismissible fade show alert-success" role="alert">
	{{ $message }}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif