@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-4">
        <h1>Employee not found</h1>
        <p>Please enter your 4-digit PIN code to verify it's you</p>
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
        <div class="keypad-row">
            <button class="keypad-btn" data-number="0">0</button>
            <button class="keypad-btn" id="backspace">
                <i class="fas fa-backspace"></i>
            </button>
        </div>
    </div>

    <div class="text-center">
        <a href="#" class="text-purple" id="resetPin">Reset my PIN Number</a>
    </div>

    <a href="{{ route('kiosk.back') }}" class="btn btn-link mt-4">
        <i class="fas fa-arrow-left"></i> Cancel
    </a>
</div>

@push('styles')
<style>
    .pin-display {
        margin: 20px 0;
    }

    .pin-dots {
        display: flex;
        gap: 15px;
    }

    .pin-dot {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background-color: #e9ecef;
        transition: background-color 0.2s;
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

    .keypad-btn {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: none;
        background-color: #f8f9fa;
        font-size: 24px;
        cursor: pointer;
        transition: background-color 0.2s;
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
            body: JSON.stringify({ pin })
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