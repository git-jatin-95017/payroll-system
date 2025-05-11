@extends('layouts.kiosk')

@section('content')
<div class="max-w-sm h-100">
    <div class="h-100 d-flex flex-column justify-content-center">
        <div class="tap-circle d-flex align-items-center text-white justify-content-center mx-auto mb-4" id="tapToBegin">
            <div class="tap-text text-center">
                <p class="fs-3 mb-0">TAP TO</p>
                <p class="fs-3 mb-0">BEGIN</p>
            </div>
        </div>
        <a href="{{ route('kiosk.back') }}" class="back-btn text-center d-flex align-items-center justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Back
        </a>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('tapToBegin').addEventListener('click', function() {
        window.location.href = "{{ route('kiosk.face-recognition') }}";
    });
</script>
@endpush