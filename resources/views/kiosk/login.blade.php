@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-5">
        <h1 class="display-4">Account Payroll</h1>
        <p class="lead">Welcome to the Kiosk System</p>
    </div>

    <div class="company-verification mb-4">
        <form id="companyForm" class="text-center">
            <div class="form-group mb-4">
                <input type="text" 
                       class="form-control form-control-lg" 
                       id="companyName" 
                       name="company_name" 
                       placeholder="Enter Company Name"
                       required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                Verify Company
            </button>
        </form>
    </div>

    <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;">
        Invalid company name. Please try again.
    </div>
</div>

@push('styles')
<style>
    .company-verification {
        width: 100%;
        max-width: 400px;
    }

    .form-control {
        border: 2px solid #6f42c1;
        padding: 1rem;
        font-size: 1.1rem;
    }

    .form-control:focus {
        border-color: #553098;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }

    .btn-primary {
        padding: 1rem 2rem;
    }
</style>
@endpush

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
            body: JSON.stringify({ company_name: companyName })
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