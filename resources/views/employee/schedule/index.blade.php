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
    <div class="schedule-grid-container">
        <form id="schedule-date-range-form" class="form-horizontal mb-3">
            <div class="row g-2 align-items-end">
                <div class="col daterange-container-main">
                    <div class="form-group">
                        <p class="mb-0 position-relative daterange-container">
                            <input type="text" name="daterange" id="schedule-daterange" class="form-control db-custom-input" value="{{date('m/d/Y', strtotime(request('start_datetime', now()->startOfWeek()))).' - '.date('m/d/Y', strtotime(request('end_datetime', now()->endOfWeek())))}}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"></path>
                            </svg>
                        </p>
                    </div>
                </div>
                <!-- <div class="col">
                    <input type="text" id="employee-name-search" class="form-control" placeholder="Search employee name..." value="{{ auth()->user()->name }}">
                </div> -->
                <div class="col-auto">
                    <button type="submit" id="search-schedule-btn" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <div class="table-responsive" style="max-height: 500px; overflow: auto;">
            <table class="table table-bordered align-middle schedule-calendar-table" id="schedule-grid-table">
                <thead>
                    <tr id="schedule-grid-header-row">
                        <th class="schedule-employee-cell" style="background-color: #4f4bc3;border-bottom: 2px solid #4f4bc3;">
                            <div class="d-flex">
                                <div class="ts-img d-flex justify-content-center align-items-center">
                                    @if(auth()->user()->profile_image)
                                        <img src="/files/{{ auth()->user()->profile_image }}" style="width: 40px; height: 40px; border-radius: 100em;" alt="avatar">
                                    @else
                                        <img src='/img/user2-160x160.jpg' style="width: 40px; height: 40px; border-radius: 100em;">
                                    @endif
                                </div>
                                <div class="col-auto ps-2">
                                    <p class="ts-user-name mb-0">{{ auth()->user()->name }}</p>
                                    <p class="ts-designation mb-0">{{ auth()->user()->designation ?? '' }}</p>
                                </div>
                            </div>
                        </th>
                        <!-- Date columns will be injected by JS -->
                    </tr>
                </thead>
                <tbody>
                    <tr id="schedule-grid-body-row">
                        <td class="schedule-employee-cell"></td>
                        <!-- Schedule cells will be injected by JS -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
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
          <label class="form-label fw-bold">Start Time:</label>
          <p id="modal-start-time" class="mb-0"></p>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">End Time:</label>
          <p id="modal-end-time" class="mb-0"></p>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Description:</label>
          <p id="modal-description" class="mb-0"></p>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" id="deleteScheduleBtn">Delete</button> -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
.schedule-calendar-table th, .schedule-calendar-table td {
    vertical-align: top;
    min-width: 120px;
    border: 1px solid #e0e0e0;
    background: #fff;
}
.schedule-calendar-table th.date-header {
    color: #fff;
    background-color: #4f4bc3;
    font-weight: normal;
    font-size: 12px;
    border-bottom: 2px solid #4f4bc3;
}
.schedule-calendar-table td { position: relative; height: 70px; }
.schedule-employee-cell { text-align: left; min-width: 180px; background: #f8f9fb; font-weight: 500; border-right: 2px solid #bdbdbd; }
.schedule-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px; }
.schedule-event-chip { display: inline-block; background: #5e72e4; color: #fff; border-radius: 12px; padding: 2px 10px; font-size: 13px; margin-bottom: 2px; margin-right: 2px; white-space: nowrap; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    let startDate = moment().startOf('week');
    let endDate = moment().endOf('week');
    $('#schedule-daterange').daterangepicker({
        startDate: startDate,
        endDate: endDate,
        locale: { format: 'MM/DD/YYYY' }
    });
    // Only load grid on initial load and on search button click
    loadScheduleGrid();
    $('#schedule-date-range-form').on('submit', function(e) {
        e.preventDefault();
        const drp = $('#schedule-daterange').data('daterangepicker');
        startDate = drp.startDate;
        endDate = drp.endDate;
        loadScheduleGrid();
    });
    function pastelColor(seed) {
        let hash = 0;
        for (let i = 0; i < seed.length; i++) hash = seed.charCodeAt(i) + ((hash << 5) - hash);
        const h = Math.abs(hash) % 360;
        return `hsl(${h}, 70%, 85%)`;
    }
    function format12hr(timeStr) {
        if (!timeStr) return '';
        const [h, m] = timeStr.split(':');
        let hour = parseInt(h);
        const min = m;
        const ampm = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12;
        if (hour === 0) hour = 12;
        return `${hour}:${min} ${ampm}`;
    }
    function loadScheduleGrid() {
        $.ajax({
            url: '{{ route('employee.schedules.ajax') }}',
            method: 'GET',
            data: {
                start_datetime: startDate.format('YYYY-MM-DD 00:00:00'),
                end_datetime: endDate.format('YYYY-MM-DD 23:59:59')
            },
            success: function(response) {
                renderScheduleGrid(response.schedules);
            },
            error: function() {
                $('#schedule-grid-table tbody').html('<tr><td colspan="100%" class="text-danger">Error loading schedules.</td></tr>');
            }
        });
    }
    let selectedScheduleId = null;
    // Open modal on chip click
    $(document).on('click', '.schedule-event-chip', function() {
        const sch = $(this).data('schedule');
        selectedScheduleId = sch.id;
        $('#modal-title').text(sch.title || '-');
        $('#modal-start-time').text(format12hr(sch.start_datetime.substr(11,5)));
        $('#modal-end-time').text(format12hr(sch.end_datetime.substr(11,5)));
        $('#modal-description').text(sch.description || '-');
        $('#scheduleDetailsModal').modal('show');
    });
    // Delete schedule
    $('#deleteScheduleBtn').on('click', function() {
        if (!selectedScheduleId) return;
        if (!confirm('Are you sure you want to delete this schedule?')) return;
        $.ajax({
            url: '/employee/my-schedules/' + selectedScheduleId,
            method: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function() {
                $('#scheduleDetailsModal').modal('hide');
                loadScheduleGrid();
            },
            error: function() {
                alert('Error deleting schedule');
            }
        });
    });
    function renderScheduleGrid(schedules) {
        // Build date columns
        let dateColumns = [];
        let dayNames = [];
        let current = moment(startDate);
        while (current <= endDate) {
            dateColumns.push(current.format('YYYY-MM-DD'));
            dayNames.push(current.format('ddd'));
            current.add(1, 'days');
        }
        // Render table header
        let searchValue = "{{ auth()->user()->name }}";
        let thead = `<tr><th class="schedule-employee-cell" style="background-color: #4f4bc3;border-bottom: 2px solid #4f4bc3;">
            <p class="db-table-search position-relative mb-0">
                <svg width="20px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M14.53 15.59a8.25 8.25 0 111.06-1.06l5.69 5.69a.75.75 0 11-1.06 1.06l-5.69-5.69zM2.5 9.25a6.75 6.75 0 1111.74 4.547.746.746 0 00-.443.442A6.75 6.75 0 012.5 9.25z"></path>
                </svg>
                <input type="text" id="employee-name-search" class="input-sm form-control" placeholder="Search employee name..." value="${searchValue}">
            </p>
            
        </th>`;
        dateColumns.forEach((date, idx) => {
            const d = moment(date);
            thead += `<th class="date-header"><span style='font-weight:bold;font-size:12px;'>${d.format('ddd')}</span><br><span style='font-size:12px;'>${d.format('DD MMM')}</span></th>`;
        });
        thead += '</tr>';
        $('#schedule-grid-table thead').html(thead);
        // Render table body
        let tbody = '<tr>';
        // Profile image in first cell of body row
        tbody += '<td class="schedule-employee-cell"><div class="d-flex"><div class="ts-img d-flex justify-content-center align-items-center">';
        @if(auth()->user()->profile_image)
            tbody += '<img src="/files/{{ auth()->user()->profile_image }}" style="width: 40px; height: 40px; border-radius: 100em;" alt="avatar">';
        @else
            tbody += '<img src="/img/user2-160x160.jpg" style="width: 40px; height: 40px; border-radius: 100em;">';
        @endif
        tbody += `</div><div class="col-auto ps-2">
                <p class="ts-user-name mb-0">{{ auth()->user()->name }}</p><p class="ts-designation mb-0">{{ auth()->user()->employeeProfile->designation ?? '' }}</p>
            </div></div></td>`;
        dateColumns.forEach(date => {
            // Find schedules for this date
            const cellSchedules = schedules.filter(s => s.start_datetime.startsWith(date));
            tbody += '<td style="position:relative">';
            if (cellSchedules.length > 0) {
                cellSchedules.forEach(sch => {
                    const color = pastelColor(sch.title+sch.id);
                    const startTime = format12hr(sch.start_datetime.substr(11,5));
                    const endTime = format12hr(sch.end_datetime.substr(11,5));
                    // Attach schedule data for modal
                    tbody += `<div class="schedule-event-chip" style="background:${color}" title="${sch.title}\n${startTime} - ${endTime}\n${sch.description||''}" data-schedule='${JSON.stringify(sch)}'> <span style='font-size:11px;'>${startTime}-${endTime}</span></div>`;
                });
            }
            tbody += '</td>';
        });
        tbody += '</tr>';
        $('#schedule-grid-table tbody').html(tbody);
    }
    // Optionally, filter by employee name (for future multi-employee support)
    $(document).on('keydown', '#employee-name-search', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            loadScheduleGrid();
        }
    });
});
</script>
@endpush 