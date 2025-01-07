@extends('layouts.new_layout')
@section('content')
<style>
    #header {
        background-color: #0d0b45;
        border-color: #0d0b45;
        position: static;
    }

    #page-container {
        padding: 0 !important;
    }

    .db-main-container {
        margin-top: -60px;
    }
</style>

<section class="dashboard-top-bg p-5">
    <div class="text-center px-4">
        <div class="admin-name pb-4">
            <h2>Hello, {{ ucwords(Auth::user()->name) }}</h2>
        </div>
    </div>
    <div class="d-flex gap-5 justify-content-center widget-container-main">
        <div class="widget-container">
            <a href="{{ route('employee.create') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-user class="w-24 h-24" />
                </div>
                <p>Add Employee</p>
            </a>
        </div>
        <div class="widget-container">
            <a href="{{ route('leave-type.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-donate-heart class="w-24 h-24" />
                </div>
                <p>Add Leave Policy</p>
            </a>
        </div>
        <div class="widget-container">
            <a href="{{ route('pay-head.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-dollar-circle class="w-24 h-24" />
                </div>
                <p>Add Pay Label</p>
            </a>
        </div>
        <div class="widget-container">
            <a href="{{ route('department.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-map class="w-24 h-24" />
                </div>
                <p>Add Location</p>
            </a>
        </div>
    </div>
</section>
<section class="px-2 db-main-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 mb-4">
                <div class="db-container px-4 py-5 shadow-sm h-100 bg-white">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading-db-container mb-4">
                                <h2>Recent Payroll</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="db-data-container p-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>{{$totalEmp}}</h3>
                                    </div>
                                    <div>
                                        <x-heroicon-s-users class="w-20 h-20" />
                                    </div>
                                </div>
                                <p>Approved Employees</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="db-data-container time-card p-3">
                                <label>Time Card</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="text" placeholder="11/27">
                                    <span>
                                        <x-heroicon-o-arrow-right class="w-20" />
                                    </span>
                                    <input type="text" placeholder="12/03">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <button class="btn d-block btn-db mb-2 w-100">Approve Employees</button>
                                <button class="btn d-block btn-db w-100">Run Payroll</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="db-container p-4 shadow-sm bg-white">
                    <div class="heading-db-container mb-4">
                        <h3>Notice Board</h3>
                    </div>
                    <div class="notice-board">
                        <div class="d-flex gap-3 align-items-center border-bottom pb-3 mb-3">
                            <div class="notice-icon shadow-sm">
                                <x-bx-envelope class="w-20 h-20" />
                            </div>
                            <div>
                                <p>Payroll processing on [Date]. Update attendance, leaves, and bank details by
                                    [Deadline
                                    Date]. Contact HR for help.
                                </p>
                                <span>15 minutes ago</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center border-bottom pb-3 mb-3">
                            <div class="notice-icon shadow-sm">
                                <x-bx-envelope class="w-20 h-20" />
                            </div>
                            <div>
                                <p>Payroll processing on [Date]. Update attendance, leaves, and bank details by
                                    [Deadline
                                    Date]. Contact HR for help.
                                </p>
                                <span>15 minutes ago</span>
                            </div>
                        </div>
                        <div class="more-notification text-center">
                            <a href="#">More Notification</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4 mb-5">
                <div class="db-container p-4 shadow-sm bg-white">
                    <div class="heading-db-container mb-4">
                        <h3>Manage your business</h3>
                    </div>
                    <div class="db-card">
                        <a href="{{ route('my-profile.edit', auth()->user()->id) }}" class="d-block mb-3">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="notice-icon shadow-sm">
                                    <x-bx-briefcase-alt-2 class="w-20 h-20" />
                                </div>
                                <div>
                                    <h3>Profile</h3>
                                    <span>Company profile and administration</span>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('payroll.create', ['week_search' => 2]) }}" class="d-block mb-3">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="notice-icon shadow-sm">
                                    <x-bx-time class="w-20 h-20" />
                                </div>
                                <div>
                                    <h3>Time</h3>
                                    <span>Track your employee’s time</span>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('holidays.index') }}" class="d-block mb-3">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="notice-icon shadow-sm">
                                    <x-bxs-plane-take-off class="w-20 h-20" />
                                </div>
                                <div>
                                    <h3>Holidays</h3>
                                    <span>Public and voluntary Holidays</span>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('leaves.index') }}" class="d-block">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="notice-icon shadow-sm">
                                    <x-bx-user class="w-20 h-20" />
                                </div>
                                <div>
                                    <h3>Leave</h3>
                                    <!-- <span>Redirects you to Leave section</span> -->
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-5">
                <div class="db-container p-4 shadow-sm bg-white">
                    <div class="heading-db-container mb-4">
                        <h3>Recent Payroll</h3>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-5">
                <div class="db-container p-4 shadow-sm bg-white">
                    <div class="heading-db-container mb-4">
                        <h3>Calendar</h3>
                    </div>
					<!-- Small Calendar Container -->
					<div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('third_party_stylesheets')
    <!-- FullCalendar CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet"> -->
	 <!-- Styles for the dots -->
	  <!-- jQuery UI Datepicker -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
    .ui-datepicker td {
        position: relative;
    }

    .ui-datepicker .event-birthday a::after {
        content: '';
        width: 8px;
        height: 8px;
        background-color: #FF5722;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
        right: 4px;
    }

    .ui-datepicker .event-leave a::after {
        content: '';
        width: 8px;
        height: 8px;
        background-color: #4CAF50;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
        right: 4px;
    }

    .ui-datepicker .event-holiday a::after {
        content: '';
        width: 8px;
        height: 8px;
        background-color: #2196F3;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
        right: 4px;
    }
</style>
@endsection

@section('third_party_scripts')
    <!-- FullCalendar JS -->
	 
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	@endsection
@push('page_scripts')
<script>
    $(function () {
        // Define your events
        var events = [
            { date: '2025-01-12', type: 'birthday' },
            { date: '2025-01-18', type: 'leave' },
            { date: '2025-01-26', type: 'holiday' },
        ];

        // Initialize the Datepicker
        $('#calendar').datepicker({
            beforeShowDay: function (date) {
                var formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
                var event = events.find(e => e.date === formattedDate);
                
                if (event) {
                    // Return true to enable the date, and add a class for styling
                    return [true, 'event-' + event.type, event.type.charAt(0).toUpperCase() + event.type.slice(1)];
                }
                return [true, ''];
            }
        });
    });


	/*
		$(function () {
			// Fetch events from your API route
			$.ajax({
				url: "",
				method: "GET",
				success: function (response) {
					var events = response;

					$('#calendar').datepicker({
						beforeShowDay: function (date) {
							var formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
							var event = events.find(e => e.date === formattedDate);
							
							if (event) {
								return [true, 'event-' + event.type, event.title];
							}
							return [true, ''];
						}
					});
				}
			});
		});
	*/
</script>

@endpush