@php
   if(auth()->user()->role_id == 3) {
      $layoutDirectory = 'layouts.new_layout';
   } else {
      $layoutDirectory = 'layouts.new_layout';
   }
@endphp

@extends($layoutDirectory)

@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div>
    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h3>Attendance</h3>
            <p class="mb-0">Track and manage employee attendance here</p>
        </div>
    </div>

    <div class="d-flex gap-3 align-items-center justify-content-between mb-4">
        <form method="GET" action="{{ route('attendance.index') }}" class="d-flex gap-3 align-items-center justify-content-between mb-4">
            <div class="search-container">
                <div class="d-flex align-items-center gap-3">
                    <p class="mb-0 position-relative search-input-container">
                        <x-heroicon-o-magnifying-glass class="search-icon" />
                        <input type="search" class="form-control" name="search" placeholder="Type here" value="{{request()->search ?? ''}}">
                    </p>
                    <button type="submit" class="btn search-btn">
                        <x-bx-filter class="w-20 h-20"/>
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if (session('message'))
    <div>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('message') }}
        </div>
    </div>
    @elseif (session('error'))
    <div>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="bg-white p-4">
        <div class="table-responsive">
            <table class="table db-custom-table" id="dataTableBuilder">
                <thead>
                    <tr>
                        <th data-column="checked_in_at">Date</th>
                        <th data-column="name">Employee</th>
                        <th data-column="checked_in_at">Check In</th>
                        <th data-column="checked_out_at">Check Out</th>
                        <th data-column="duration">Duration</th>
                        <th data-column="note">Note</th>
                        <th data-column="status">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#dataTableBuilder').DataTable({
            processing: true,
            serverSide: true,
            scrollY: "400px",
            ajax: {
                url: "{{route('attendance.getData')}}",
                type: 'GET',
                data: function(d) {
                    d.search = $('input[name="search"]').val();
                }
            },
            columns: [
                {data: 'date', name: 'checked_in_at'},
                {data: 'employee', name: 'name'},
                {data: 'check_in', name: 'checked_in_at'},
                {data: 'check_out', name: 'checked_out_at'},
                {data: 'duration', name: 'duration', orderable: false},
                {data: 'note', name: 'note'},
                {data: 'status', name: 'status', orderable: false}
            ],
            "columnDefs": [{
                "targets": [0, 1],
                "className": "dt-center"
            }],
            orderCellsTop: true
        });

        // Handle search form submission
        $('form').on('submit', function(e) {
            e.preventDefault();
            table.draw();
        });
    });
</script>
@endpush