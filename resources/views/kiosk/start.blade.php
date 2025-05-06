@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-5">
        <h1 class="display-4">Account Payroll</h1>
        <p class="lead">Welcome to the Kiosk System</p>
    </div>

    <div class="tap-circle" id="tapToBegin">
        <div class="tap-text">
            <h2>TAP TO</h2>
            <h2>BEGIN</h2>
        </div>
    </div>

    <a href="{{ route('kiosk.back') }}" class="btn btn-link mt-4">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

@push('styles')
<style>
    .tap-circle {
        width: 200px;
        height: 200px;
        background-color: #6f42c1;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .tap-circle:hover {
        transform: scale(1.05);
    }

    .tap-text {
        color: white;
        text-align: center;
    }

    .tap-text h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('tapToBegin').addEventListener('click', function() {
    window.location.href = "{{ route('kiosk.face-recognition') }}";
});
</script>
@endpush 