@extends('layouts.new_layout')

@section('title', 'My Schedules')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
    <div>
        <h3>My Schedules</h3>
        <p class="mb-0">View your assigned schedules</p>
    </div>
</div>

<div class="bg-white w-100 border-radius-15 p-4">
    <div id="calendar"></div>
</div>

<!-- Schedule Details Modal -->
<div class="modal fade" id="scheduleDetailsModal" tabindex="-1" aria-labelledby="scheduleDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleDetailsModalLabel">Schedule Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Title:</label>
          <p id="modal-title" class="mb-0"></p>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Start Date:</label>
          <p id="modal-start-date" class="mb-0"></p>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">End Date:</label>
          <p id="modal-end-date" class="mb-0"></p>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Description:</label>
          <p id="modal-description" class="mb-0"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                right: 'prev,next',
                left: 'title',
                center: ''
            },
            dayMaxEventRows: 0,
            eventDisplay: 'list-item',
            eventClick: function(info) {
                // Show schedule details in modal
                $('#modal-title').text(info.event.title);
                $('#modal-start-date').text(info.event.startStr);
                $('#modal-end-date').text(info.event.endStr || info.event.startStr);
                $('#modal-description').text(info.event.extendedProps.description || 'No description provided');
                $('#scheduleDetailsModal').modal('show');
            },
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '{{ route("my-schedules") }}',
                    method: 'GET',
                    data: {
                        start_date: fetchInfo.startStr,
                        end_date: fetchInfo.endStr,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        const events = data.schedules.map(schedule => ({
                            id: schedule.id,
                            title: schedule.title,
                            start: schedule.start_datetime,
                            end: schedule.end_datetime,
                            backgroundColor: '#5E5ADB',
                            borderColor: '#5E5ADB',
                            extendedProps: {
                                description: schedule.description
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
</script>
@endpush 