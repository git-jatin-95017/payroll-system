@extends('layouts.kiosk')
@section('content')
<div class="max-w-sm h-100">
    <div class="h-100 d-flex flex-column justify-content-center">
        <div class="login-logo text-center mb-4">
            <img src="{{ asset('img/logo-kiosk.png') }}" alt="logo">
        </div>
        <div class="company-verification mb-4">
            <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;">
                Invalid company name. Please try again.
            </div>
            <form id="companyForm">
                <label class="fs-6 fw-medium mb-2">Company Name</label>
                <div class="form-group mb-4">
                    <input type="text"
                        class="form-control site-input-lg"
                        id="companyName"
                        name="company_name"
                        placeholder="Enter Company Name"
                        required>
                </div>
                <button type="submit" class="btn btn-verify">
                    Verify Company
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('companyForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const companyName = document.getElementById('companyName').value;
        const errorMessage = document.getElementById('errorMessage');

        try {
            const response = await fetch('{{ route("kiosk.verify-company") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    company_name: companyName
                })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = '{{ route("kiosk.start") }}';
            } else {
                errorMessage.style.display = 'block';
                errorMessage.textContent = data.message || 'Invalid company name. Please try again.';
            }
        } catch (err) {
            console.error("Error verifying company:", err);
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'An error occurred. Please try again.';
        }
    });
</script>
@endpush