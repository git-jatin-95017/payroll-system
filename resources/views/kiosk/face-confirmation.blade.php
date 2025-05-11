@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-4">
        <h2>Are you {{ $user->name }}?</h2>
        @if(session('kiosk_captured_face'))
            <div class="employee-photo-figma mb-4 mx-auto">
                <img src="{{ session('kiosk_captured_face') }}" alt="Captured Face" class="employee-photo-img">
            </div>
        @endif
        <form method="POST" action="{{ route('kiosk.face-confirmation.post') }}" class="d-flex justify-content-center gap-3">
            @csrf
            <button type="submit" name="confirm" value="yes" class="btn btn-success btn-lg" style="width:100px;">Yes</button>
            <button type="submit" name="confirm" value="no" class="btn btn-danger btn-lg" style="width:100px;">No</button>
        </form>
        <a href="{{ route('kiosk.back') }}" class="back-btn text-center d-flex align-items-center justify-content-center mt-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Cancel
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
.employee-photo-figma {
    display: flex;
    justify-content: center;
    align-items: center;
}
.employee-photo-img {
    width: 120px;
    height: 120px;
    object-fit: contain;
    border-radius: 0; /* Perfectly square corners */
    /*border: 3px solid #cbcbcb;  Match camera square grey */
    box-shadow: none;
    background: #fff;
}
</style>
@endpush
