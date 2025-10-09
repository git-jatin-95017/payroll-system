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
            <a href="{{ route('employee.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-user class="w-24 h-24" />
                </div>
                <p>People</p>
            </a>
        </div>
        <div class="widget-container">
            <a href="{{ route('leave-type.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-donate-heart class="w-24 h-24" />
                </div>
                <p>Leave Policies</p>
            </a>
        </div>
        <div class="widget-container">
            <a href="{{ route('pay-head.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-dollar-circle class="w-24 h-24" />
                </div>
                <p>Pay Labels</p>
            </a>
        </div>
        <div class="widget-container">
            <a href="{{ route('department.index') }}" class="d-flex flex-column align-items-center">
                <div class="widget-icon d-flex justify-content-center align-items-center">
                    <x-bxs-map class="w-24 h-24" />
                </div>
                <p>Locations</p>
            </a>
        </div>
    </div>
</section>
<section class="px-2 db-main-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 mb-4">
                <div class="db-container p-4 shadow-sm bg-white h-100">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading-db-container mb-4">
                                <h3>Payroll</h3>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="db-data-container time-card py-2 px-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 id="employeeCount">{{$totalEmp}}</h3>
                                    </div>
                                    <div>
                                        <x-heroicon-s-users class="w-20 h-20" />
                                    </div>
                                </div>
                                <p>Approved Employees</p>
                            </div>
                        </div>
                        <form class="" method="GET" action="{{ route('payroll.create') }}" id="filter-timesheet">

                            <div class="col-12">
                                <div class="db-data-container time-card py-2 px-3">
                                    <label>Time sheet</label>
                                    <?php
                                $requestData['start_date'] = date('Y-m-d', strtotime('-1 week'));

                                $requestData['end_date'] = date('Y-m-d');
                                ?>
                                    <div class="custom-calender">
                                        <input type="text" class="form-control" name="daterange"
                                            value="{{date('m/d/Y', strtotime($requestData['start_date'])).' - '.date('m/d/Y', strtotime($requestData['end_date']))}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div>
                                    <button type="submit" class="btn d-block btn-db mb-3 w-100">Approved
                                        Employees</button>
                                    <a href="{{ route('list.payroll') }}" class="btn d-block btn-db w-100">Run
                                        Payroll</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="db-container p-4 shadow-sm bg-white h-100">
                    <div class="heading-db-container mb-4">
                        <h3>Recent Payrolls</h3>
                    </div>
                    <canvas id="payrollChart" width="300" height="300" style="max-width: 100%;"></canvas>
                    <div class="pay-period-container mt-2">
                        <span id="pay-period"></span>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4" x-data="noticeBoard">
                <div class="db-container p-4 shadow-sm bg-white h-100">
                    <div class="heading-db-container mb-4">
                        <h3>Notice Board</h3>
                    </div>
                    <div class="notice-board">
                        <template x-for="notice in notices" :key="notice.id">
                            <div class="d-flex gap-3 align-items-center border-bottom pb-3 mb-3">
                                <div class="notice-icon shadow-sm">
                                    <a href="{{ route('notice.index') }}"><x-bx-envelope class="w-20 h-20" /></a>
                                </div>
                                <div>
                                    <p x-text="truncateMessage(notice.message)"></p>
                                    <span x-text="timeAgo(notice.created_at)"></span>
                                </div>
                            </div>
                        </template>
                        <div class="more-notification text-center">
                            <a href="{{ route('notice.index') }}">More Notification</a>
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
                        <a href="{{ route('my-profile.edit', auth()->user()->id) }}" class="d-block mb-4">
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
                        <a href="{{ route('payroll.create', ['week_search' => 2]) }}" class="d-block mb-4">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="notice-icon shadow-sm">
                                    <x-bx-time class="w-20 h-20" />
                                </div>
                                <div>
                                    <h3>Time</h3>
                                    <span>Track your employees time</span>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('holidays.index') }}" class="d-block mb-4">
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
                                    <span>Track your employees leave</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-8 mb-4">
                <div class="db-container py-4 px-3 shadow-sm bg-white">
                    <!-- <div class="heading-db-container mb-4">
                        <h3>Calendar</h3>
                    </div> -->
                    <!-- Small Calendar Container -->
                    <div class="custom-calendar">
                        <div id="calendar"></div>
                    </div>
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

    .small,
    small {
        font-size: .75em !important;
    }
</style>
@endsection

@section('third_party_scripts')
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection

@push('page_scripts')

<script>
    $(function () {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            startDate: moment('{{ $requestData['start_date'] }}'),
            endDate: moment('{{ $requestData['end_date'] }}'),
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            // Send the selected date range via AJAX
            updateEmployeeCount(start, end);
        });

        // Trigger the function with the initial date range
        const initialStartDate = moment('{{ $requestData['start_date'] }}');
        const initialEndDate = moment('{{ $requestData['end_date'] }}');
        updateEmployeeCount(initialStartDate, initialEndDate);

        // Function to update employee count based on date range
        function updateEmployeeCount(start, end) {
            const startDate = start.format('YYYY-MM-DD');
            const endDate = end.format('YYYY-MM-DD');

            // Ajax Request
            $.ajax({
                url: '{{ route("getApprovedEmployeesCount") }}', // Define your route for getting employee count
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (response) {
                    // Update the employee count in the DOM
                    $('#employeeCount').text(response.totalEmp);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching employee count: ", error);
                }
            });
        }

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                //height: 480,
                headerToolbar: {
                    right: 'prev,next',
                    left: 'title',
                    center: '' // user can switch between the two
                },
                // headerToolbar: {
                //     left: 'prev',
                //     center: 'title',
                //     right: 'next'
                // },
                // dayMaxEvents: true,  // Enable "+ more" link when too many events
                // eventLimit: true,
                dayMaxEventRows: 0, // Show max 4 events per day, then display "+ more"
                // moreLinkClick: 'popover',
                eventDisplay: 'list-item',
                // dayCellContent: function (arg) {
                //     // Show a dot if there are events on that date
                //     let events = calendar.getEvents().filter(event => event.startStr === arg.dateStr);
                //     if (events.length > 0) {
                //         return '<div class="dot"></div>';
                //     }
                // },
                // dayCellDidMount: function (info) {
                //     // Optionally add more interactions if needed
                // },
                // eventClick: function (info) {
                //     // Use FullCalendar's default functionality to show event details
                //     info.jsEvent.preventDefault(); // Prevent browser default behavior

                //     // You can use FullCalendar's built-in event details modal or customize this behavior
                //     alert('Event: ' + info.event.title + '\nDate: ' + info.event.start.toISOString());
                // },
                // eventContent: function (info) {
                //     var dot = document.createElement('div');
                //     dot.className = 'custom-dot ' + info.event.extendedProps.type;

                //     // Initialize Tippy.js tooltip
                //     // tippy(dot, {
                //     //     content: info.event.title,
                //     //     theme: 'light'
                //     // });

                //     return { domNodes: [dot] };
                // },
                // eventContent: function (arg) {
                //     // Custom content with a dot and title
                //     return {
                //         html: `<span class="fc-event-dot" style="background-color:${arg.event.backgroundColor};"></span> ${arg.event.title}`
                //     };
                // },
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
                },
                // dayCellContent: function (arg) {
                //     // Show a dot if there are any events on that date
                //     let events = calendar.getEvents().filter(event => event.startStr === arg.dateStr);
                //     if (events.length > 0) {
                //         return '<div class="dot"></div>';
                //     }
                // },
                // dayCellDidMount: function (info) {
                //     // Get all events for the current date
                //     let events = calendar.getEvents().filter(event => event.startStr === info.dateStr);

                //     // If there are events, add a Tippy tooltip on the dot
                //     if (events.length > 0) {
                //         let eventTitles = events.map(event => `<strong>${event.title}</strong><br>`).join('');

                //         tippy(info.el.querySelector('.dot'), {
                //             content: eventTitles,
                //             interactive: true,
                //             allowHTML: true,
                //             theme: 'light',
                //         });
                //     }
                // }
            });

            calendar.render();
        }
    });
</script>

<script>
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Chart instance
        const ctx = document.getElementById('payrollChart').getContext('2d');
        // Create the bar chart
        const payrollChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Labels will be populated via AJAX
                datasets: [{
                    label: '',
                    data: [], // Data will be populated via AJAX
                    backgroundColor: ['#5E5ADB', '#5E5ADB'],
                    borderColor: ['#5E5ADB', '#5E5ADB'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 500,
                        ticks: {
                            // Format Y-axis labels with $
                            callback: function (value) {
                                return formatter.format(value);
                            }
                        },
                        font: {
                            size: 10 // Smaller Y-axis font size
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10 // Smaller X-axis font size
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return 'Total: ' + formatter.format(tooltipItem.raw);
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
        // AJAX request to fetch recent payroll data
        function loadPayrollData() {
            fetch('/client/recent-payroll')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.month);
                    const amounts = data.map(item => item.total_amount);

                    // Update the chart with new data
                    payrollChart.data.labels = labels;
                    payrollChart.data.datasets[0].data = amounts;
                    payrollChart.update();

                    console.log(data);

                    // Sort the data in descending order of the original index
                    data.reverse();


                    // Dynamically create HTML for pay periods
                    let html = '<div class="pay-container mt-3"><h3>Pay - Period</h3>';

                    if (data.length > 0) {
                        data.forEach(item => {
                            if (item.dateRange && item.total_amount !== undefined) {
                                html += `
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <span class="pay-period-time">${item.dateRange}</span>
                                        <span class="pay-period-amount">${formatter.format(item.total_amount)}</span>
                                    </div>
                                `;
                            }
                        });
                    } else {
                        html += '<p>No payroll data available.</p>';
                    }

                    html += '</div>';

                    // Update the span with the dynamically generated HTML
                    $('#pay-period').html(html);
                })
                .catch(error => {
                    console.error('Error fetching payroll data:', error);
                });
        }

        // Load data on page load
        loadPayrollData();
    });
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('noticeBoard', () => ({
            notices: [],

            init() {
                this.fetchNotices();
                setInterval(() => this.fetchNotices(), 2000); // Refresh every 5 seconds
            },

            fetchNotices() {
                fetch('/client/notices')
                    .then(res => res.json())
                    .then(data => {
                        this.updateNotices(data);
                    })
                    .catch(err => console.error('Error fetching notices:', err));
            },

            updateNotices(newNotices) {
                // Check if the notices have changed and update if necessary
                if (JSON.stringify(this.notices) !== JSON.stringify(newNotices)) {
                    this.notices = newNotices;
                }
            },

            timeAgo(date) {
                const diff = Math.floor((new Date() - new Date(date)) / 60000);
                if (diff < 60) return `${diff} minutes ago`;
                const hours = Math.floor(diff / 60);
                return hours === 1 ? `1 hour ago` : `${hours} hours ago`;
            },
            truncateMessage(message) {
                return message.length > 60 ? message.substring(0, 60) + '...' : message;
            }
        }));
    });
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('noticeBoard', () => ({
            notices: [],

            init() {
                this.fetchNotices();
                setInterval(() => this.fetchNotices(), 2000); // Refresh every 5 seconds
            },

            fetchNotices() {
                fetch('/client/notices')
                    .then(res => res.json())
                    .then(data => {
                        this.updateNotices(data);
                    })
                    .catch(err => console.error('Error fetching notices:', err));
            },

            updateNotices(newNotices) {
                // Check if the notices have changed and update if necessary
                if (JSON.stringify(this.notices) !== JSON.stringify(newNotices)) {
                    this.notices = newNotices;
                }
            },

            timeAgo(date) {
                const diff = Math.floor((new Date() - new Date(date)) / 60000);
                if (diff <= 0) return 'Just now';
                if (diff < 60) return `${diff} minutes ago`;
                const hours = Math.floor(diff / 60);
                return hours === 1 ? `1 hour ago` : `${hours} hours ago`;
            },
            truncateMessage(message) {
                return message.length > 120 ? message.substring(0, 120) + '...' : message;
            }
        }));
    });
</script>

@endpush