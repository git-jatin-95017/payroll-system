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
                                <?php
                                $requestData['start_date'] = date('Y-m-d', strtotime('-1 week'));

                                $requestData['end_date'] = date('Y-m-d');
                                ?>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="text" placeholder="{{date('m/d', strtotime('-1 week'))}}">
                                    <span>
                                        <x-heroicon-o-arrow-right class="w-20" />
                                    </span>
                                    <input type="text" placeholder="{{date('m/d')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <a href="{{ route('employee.index') }}" class="btn d-block btn-db mb-2 w-100">Approved Employees</a>
                                <a href="{{ route('payroll.create', ['week_search' => 2, 'start_date' => $requestData['start_date'], 'end_date' => $requestData['end_date']]) }}" class="btn d-block btn-db w-100">Run Payroll</a>
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
					<div class="custom-calendar" id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('third_party_stylesheets')
    <!-- FullCalendar CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">

    <!-- Custom CSS for dots -->
    <style>
        .custom-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            background-color: #3b82f6;
        }

        .custom-dot.birthday {
            background-color: #fbbf24;
        }

        .custom-dot.leave {
            background-color: #10b981;
        }

        .custom-dot.public_holiday {
            background-color: #ef4444;
        }

        .custom-dot.voluntary_holiday {
            background-color: #6366f1;
        }

        .tippy-box[data-theme~='light'] {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('third_party_scripts')
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },

                eventContent: function (info) {
                    var dot = document.createElement('div');
                    dot.className = 'custom-dot ' + info.event.extendedProps.type;

                    // Initialize Tippy.js tooltip
                    tippy(dot, {
                        content: info.event.title,
                        theme: 'light'
                    });

                    return { domNodes: [dot] };
                },
                events: function (fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/client/fetch-calendar-data',
                        method: 'GET',
                        success: function (data) {
                            successCallback(data);
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