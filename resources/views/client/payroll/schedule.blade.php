@extends('layouts.new_layout')

@section('content')
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.css' rel='stylesheet' />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
    <div>
        <h3>Schedules</h3>
        <p class="mb-0">View and manage employee schedules</p>
    </div>
</div>

<div class="bg-white w-100 border-radius-15 p-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="employeeSelect" class="form-label">Filter By Employee: </label>
            <select class="form-select select2" id="employeeSelect">
                <option value="">All Employees</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->employeeProfile->full_name ?? $employee->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div id="calendar"></div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleModalLabel">Add Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="scheduleForm">
          <input type="hidden" name="selected_date" id="selected_date">
          <div class="mb-3">
            <label for="modal_employee_id" class="form-label">Select Employee *</label>
            <select class="form-select select2" id="modal_employee_id" name="employee_id" required>
              <option value="">Choose an employee...</option>
              @foreach($employees as $employee)
              <option value="{{ $employee->id }}">{{ $employee->employeeProfile->full_name ?? $employee->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="title" class="form-label">Schedule Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
          </div>
          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
          </div>
          <button type="submit" class="btn btn-success">Save Schedule</button>
          <button type="button" class="btn btn-danger d-none" id="deleteScheduleBtn">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page_scripts')
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let calendar;
let currentScheduleId = null;

// Set up CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Initialize Select2 for searchable dropdowns
$(document).ready(function() {
    // Initialize main employee filter dropdown
    $('#employeeSelect').select2({
        placeholder: 'All Employees',
        allowClear: true,
        width: '100%'
    });
    
    // Initialize modal employee dropdown
    $('#modal_employee_id').select2({
        placeholder: 'Choose an employee...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#scheduleModal')
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                right: 'prev,next',
                left: 'title',
                center: ''
            },
            dayMaxEventRows: 0,
            eventDisplay: 'list-item',
            selectable: true,
            select: function(info) {
                // When a date is clicked, open the modal
                $('#selected_date').val(info.startStr);
                $('#start_date').val(info.startStr);
                $('#end_date').val(info.startStr);
                $('#modal_employee_id').val($('#employeeSelect').val()).trigger('change'); // Pre-fill with main dropdown selection
                $('#scheduleForm')[0].reset();
                $('#deleteScheduleBtn').addClass('d-none');
                currentScheduleId = null;
                $('#scheduleModalLabel').text('Add Schedule');
                $('#scheduleModal').modal('show');
            },
            eventClick: function(info) {
                // When an event is clicked, show details and allow editing
                currentScheduleId = info.event.id;
                $('#modal_employee_id').val(info.event.extendedProps.employee_id).trigger('change');
                $('#title').val(info.event.title);
                $('#start_date').val(info.event.startStr);
                $('#end_date').val(info.event.endStr);
                $('#description').val(info.event.extendedProps.description);
                $('#deleteScheduleBtn').removeClass('d-none');
                $('#scheduleModalLabel').text('Edit Schedule');
                $('#scheduleModal').modal('show');
            },
            events: function (fetchInfo, successCallback, failureCallback) {
                const employeeId = $('#employeeSelect').val();
                $.ajax({
                    url: '/client/schedule',
                    method: 'GET',
                    data: {
                        start_date: fetchInfo.startStr,
                        end_date: fetchInfo.endStr,
                        employee_id: employeeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        const events = data.schedules.map(schedule => ({
                            id: schedule.id,
                            title: schedule.title,
                            start: schedule.start_date,
                            end: schedule.end_date,
                            backgroundColor: '#5E5ADB',
                            borderColor: '#5E5ADB',
                            extendedProps: {
                                description: schedule.description,
                                employee_id: schedule.employee_id
                            }
                        }));
                        successCallback(events);
                    },
                    error: function () {
                        failureCallback();
                    }
                });
            }
        });

        calendar.render();
    }
});

$('#employeeSelect').on('change', function() {
    if (calendar) {
        calendar.refetchEvents();
    }
});

$('#scheduleForm').on('submit', function(e) {
    e.preventDefault();
    
    // Validate that employee is selected
    const employeeId = $('#modal_employee_id').val();
    if (!employeeId) {
        alert('Please select an employee first.');
        return;
    }
    
    let formDataString = $(this).serialize();
    formDataString += '&_token={{ csrf_token() }}';
    let url = '/client/schedule';
    let method = 'POST';
    
    if (currentScheduleId) {
        url += '/' + currentScheduleId;
        method = 'PUT';
    }
    
    $.ajax({
        url: url,
        method: method,
        data: formDataString,
        success: function(response) {
            alert('Schedule saved successfully!');
            $('#scheduleModal').modal('hide');
            calendar.refetchEvents();
        },
        error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errorMessage = 'Please fix the following errors:\n';
                Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                    errorMessage += '- ' + xhr.responseJSON.errors[key][0] + '\n';
                });
                alert(errorMessage);
            } else {
                alert('Error saving schedule.');
            }
        }
    });
});

$('#deleteScheduleBtn').on('click', function() {
    if (currentScheduleId && confirm('Are you sure you want to delete this schedule?')) {
        $.ajax({
            url: '/client/schedule/' + currentScheduleId,
            method: 'DELETE',
            data: { 
                _token: '{{ csrf_token() }}' 
            },
            success: function(response) {
                alert('Schedule deleted successfully!');
                $('#scheduleModal').modal('hide');
                calendar.refetchEvents();
            },
            error: function(xhr) {
                alert('Error deleting schedule.');
            }
        });
    }
});
</script>
@endpush 