@extends('layouts.kiosk')
@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-2">
        <h1 class="fs-5 fw-semibold mb-1">Employee not found</h1>
        <p class="text-sm text-gray">Please enter your 4-digit PIN code to verify it's you</p>
    </div>

    <div class="pin-display mb-4">
        <div class="pin-dots">
            <span class="pin-dot"></span>
            <span class="pin-dot"></span>
            <span class="pin-dot"></span>
            <span class="pin-dot"></span>
        </div>
    </div>

    <div class="keypad mb-4">
        <div class="keypad-row">
            <button class="keypad-btn" data-number="1">1</button>
            <button class="keypad-btn" data-number="2">2</button>
            <button class="keypad-btn" data-number="3">3</button>
        </div>
        <div class="keypad-row">
            <button class="keypad-btn" data-number="4">4</button>
            <button class="keypad-btn" data-number="5">5</button>
            <button class="keypad-btn" data-number="6">6</button>
        </div>
        <div class="keypad-row">
            <button class="keypad-btn" data-number="7">7</button>
            <button class="keypad-btn" data-number="8">8</button>
            <button class="keypad-btn" data-number="9">9</button>
        </div>
        <div class="keypad-row justify-content-end">
            <button class="keypad-btn" data-number="0">0</button>
            <button class="keypad-btn " style="background: transparent;" id="backspace">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M31.5 6H12L1.5 18L12 30H31.5C32.2956 30 33.0587 29.6839 33.6213 29.1213C34.1839 28.5587 34.5 27.7956 34.5 27V9C34.5 8.20435 34.1839 7.44129 33.6213 6.87868C33.0587 6.31607 32.2956 6 31.5 6Z" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M27 13.5L18 22.5" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18 13.5L27 22.5" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    <div class="mb-2">
        <div class="text-center mb-2">
            <a href="#" class="text-purple text-sm" id="resetPin">Reset my PIN Number</a>
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
@push('styles')
<style>
    .pin-display {
        margin: 20px 0;
    }

    .pin-dots {
        display: flex;
        gap: 15px;
    }

    .pin-dot.filled {
        background-color: #6f42c1;
    }

    .keypad {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 300px;
    }

    .keypad-row {
        display: flex;
        gap: 10px;
        justify-content: center;
    }



    .keypad-btn:hover {
        background-color: #e9ecef;
    }

    .text-purple {
        color: #6f42c1;
        text-decoration: none;
    }

    .text-purple:hover {
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<script>
    let pin = '';

    function updatePinDisplay() {
        const dots = document.querySelectorAll('.pin-dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('filled', index < pin.length);
        });

        if (pin.length === 4) {
            verifyPin();
        }
    }

    async function verifyPin() {
        try {
            const response = await fetch('{{ route("kiosk.verify-pin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pin
                })
            });

            const data = await response.json();

            if (data.success) {
                // Store user data in session and redirect to clock in/out
                sessionStorage.setItem('user', JSON.stringify(data.user));
                window.location.href = '{{ route("kiosk.clock") }}';
            } else {
                pin = '';
                updatePinDisplay();
                alert('Invalid PIN. Please try again.');
            }
        } catch (err) {
            console.error("Error verifying PIN:", err);
        }
    }

    document.querySelectorAll('.keypad-btn[data-number]').forEach(button => {
        button.addEventListener('click', () => {
            if (pin.length < 4) {
                pin += button.dataset.number;
                updatePinDisplay();
            }
        });
    });

    document.getElementById('backspace').addEventListener('click', () => {
        pin = pin.slice(0, -1);
        updatePinDisplay();
    });

    document.getElementById('resetPin').addEventListener('click', (e) => {
        e.preventDefault();
        // Implement PIN reset functionality
        alert('Please contact your administrator to reset your PIN.');
    });
</script>
@endpush