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