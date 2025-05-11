@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-4">
        <h1>Time Clock</h1>
        <p id="welcomeMessage"></p>
        <div id="currentTime" class="display-4 mb-3"></div>
    </div>

    <div class="clock-actions text-center mb-4">
        <button id="clockButton" class="btn btn-primary btn-lg mb-3" style="min-width: 200px;">
            <i class="fas fa-clock me-2"></i>
            <span id="clockButtonText">Loading...</span>
        </button>
        
        <div class="mt-3">
            <textarea id="noteInput" class="form-control mb-3" rows="2" placeholder="Add a note (optional)"></textarea>
        </div>
    </div>

    <!-- Today's History -->
    <div class="history-section mb-4">
        <h3 class="text-center mb-3">Today's Activity</h3>
        <div id="historyList" class="list-group" style="min-width: 300px;">
            <!-- History items will be added here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let user = null;
let clockedIn = false;

function updateCurrentTime() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString();
}

async function loadHistory() {
    try {
        const response = await fetch(`{{ route('kiosk.history') }}?user_id=${user.id}`, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            const historyList = document.getElementById('historyList');
            historyList.innerHTML = ''; // Clear existing items
            
            if (data.history.length === 0) {
                historyList.innerHTML = `
                    <div class="text-center text-muted">
                        <p>No activity recorded today</p>
                    </div>
                `;
                return;
            }

            data.history.forEach(record => {
                const item = document.createElement('div');
                item.className = 'list-group-item';
                
                // Parse dates
                const checkIn = record.checked_in_at ? new Date(record.checked_in_at) : null;
                const checkOut = record.checked_out_at ? new Date(record.checked_out_at) : null;
                
                // Format times and status
                let checkInTime = checkIn ? checkIn.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                }) : 'Not recorded';
                
                let checkOutTime = '';
                let durationText = '';
                
                if (checkOut) {
                    checkOutTime = checkOut.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });
                    
                    // Calculate duration with seconds precision
                    if (checkIn) {
                        const durationSeconds = Math.round((checkOut - checkIn) / 1000); // total seconds
                        const hours = Math.floor(durationSeconds / 3600);
                        const minutes = Math.floor((durationSeconds % 3600) / 60);
                        const seconds = durationSeconds % 60;
                        durationText = `<div class="text-muted">Duration: ${hours}h ${minutes}m ${seconds}s</div>`;
                    }
                }
                
                // Format date for display
                const dateStr = checkIn ? checkIn.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                }) : '';
                
                let note = record.note ? 
                    `<div class="text-muted">Note: ${record.note}</div>` : '';
                
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="mb-1">
                                <span class="badge bg-secondary">${dateStr}</span>
                                <span class="ms-2">${checkInTime}</span>
                                ${checkOutTime ? ` - ${checkOutTime}` : ''}
                            </div>
                            ${durationText}
                            ${note}
                        </div>
                    </div>
                `;
                
                historyList.appendChild(item);
            });
        }
    } catch (err) {
        console.error('Error loading history:', err);
    }
}

async function checkClockStatus() {
    try {
        const response = await fetch(`{{ route('kiosk.status') }}?user_id=${user.id}`, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        clockedIn = data.is_clocked_in;
        updateButtonState();
    } catch (err) {
        console.error('Error checking clock status:', err);
    }
}

function updateButtonState() {
    const button = document.getElementById('clockButton');
    const buttonText = document.getElementById('clockButtonText');
    
    if (clockedIn) {
        button.classList.remove('btn-primary');
        button.classList.add('btn-danger');
        buttonText.textContent = 'Clock Out';
    } else {
        button.classList.remove('btn-danger');
        button.classList.add('btn-primary');
        buttonText.textContent = 'Clock In';
    }
}

async function handleClockAction() {
    const button = document.getElementById('clockButton');
    button.disabled = true;  // Disable button immediately
    
    try {
        // First make the clock in/out request
        const note = document.getElementById('noteInput').value;
        const formData = new FormData();
        formData.append('user_id', user.id);
        formData.append('note', note);
        formData.append('_token', '{{ csrf_token() }}');

        const response = await fetch('{{ route("kiosk.clock-in-out") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData,
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            if (data.action === 'clock_in') {
                // Update UI for clock in
                clockedIn = true;
                updateButtonState();
                document.getElementById('noteInput').value = '';
                
                // Show success message
                alert('Successfully clocked in at ' + new Date().toLocaleTimeString());
                
                // Refresh history to show new clock in
                await loadHistory();
                button.disabled = false;
            } else if (data.action === 'clock_out') {
                // Show clock out time before redirecting
                alert('Successfully clocked out at ' + new Date().toLocaleTimeString());
                
                // Clear storage and redirect
                sessionStorage.clear();
                localStorage.clear();
                window.location.href = '/';
            }
        } else {
            alert(data.message || 'An error occurred');
            button.disabled = false;
        }
    } catch (err) {
        console.error('Error:', err);
        alert('Failed to process request. Please try again.');
        button.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const userData = sessionStorage.getItem('user');
    if (!userData) {
        window.location.href = '{{ route("kiosk.login") }}';
        return;
    }

    user = JSON.parse(userData);
    document.getElementById('welcomeMessage').textContent = `Welcome, ${user.name}`;
    
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    
    await Promise.all([
        checkClockStatus(),
        loadHistory()
    ]);
    
    document.getElementById('clockButton').addEventListener('click', handleClockAction);
});
</script>
@endpush

@push('styles')
<style>
.clock-actions {
    max-width: 400px;
    width: 100%;
}

#currentTime {
    font-size: 3rem;
    font-weight: bold;
    color: #6f42c1;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.25rem;
}

.history-section {
    max-width: 500px;
    width: 100%;
}

.list-group-item {
    border-left: 4px solid #6f42c1;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.8em;
}

.btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
</style>
@endpush 