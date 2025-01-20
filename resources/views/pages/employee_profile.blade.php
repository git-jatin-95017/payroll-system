@extends('layouts.new_layout')
@section('content')
<section>

    <div class="page-heading d-flex justify-content-between align-items-center gap-3 mb-3">
		<div>
			<h3>Settings</h3>
			<p class="mb-0">Track and manage employee profile here</p>
		</div>
    </div>
    <div class="bg-cover-container d-flex gap-5 px-4 pb-3 mb-5">
        <div class="emp-proifle-picture">
            <img src="{{ asset('img/emp1.png') }}" alt="profile">
        </div>
        <div>
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="profile-name-container pt-5">
                    <h3>Daniel John</h3>
                    <p>Senior Director of HR</p>
                </div>
                <div>
                    <ul class="nav nav-tabs nav-pills db-custom-tabs gap-5 employee-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company"
                                type="button" role="tab" aria-controls="company" aria-selected="true">Company Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
                                role="tab" aria-controls="payment" aria-selected="false">Payment Method</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button"
                                role="tab" aria-controls="admin" aria-selected="false">Administrators</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button"
                                role="tab" aria-controls="password" aria-selected="false">Password</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-4">
        <div class="employee-profile-left">
            <div class="bg-white p-4 border-radius-15">
                <div class="d-flex gap-2 employee-info align-items-center mb-2">
                    <div>
                        <x-heroicon-o-map-pin class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">London (UK)</p>
                    </div>
                </div>
                <div class="d-flex gap-2 employee-info align-items-center mb-3">
                    <div>
                        <x-heroicon-o-envelope class="w-20 h-20" />
                    </div>
                    <div>
                        <p class="mb-0">daniel.john@gmail.com</p>
                    </div>
                </div>
                <ul class="mb-0 p-0 d-flex align-items-center gap-3 employee-social-media">
                    <li>
                        <a href="#">
                            <x-bxl-facebook-square class="w-24 h-24" />
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <x-bxl-linkedin-square class="w-24 h-24" />
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_353_4505)">
                                    <path d="M15.7508 0.960938H18.8175L12.1175 8.61927L20 19.0384H13.8283L8.995 12.7184L3.46333 19.0384H0.395L7.56167 10.8468L0 0.961771H6.32833L10.6975 6.73844L15.7508 0.960938ZM14.675 17.2034H16.3742L5.405 2.7001H3.58167L14.675 17.2034Z" fill="#454E97"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_353_4505">
                                    <rect width="22" height="22" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-white w-100 border-radius-15 p-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                    <div class="max-w-md max-auto">
                        <div class="sub-text-heading pb-4">
                            <h3 class="mb-1">My Profile Information</h3>
                            <p>Type your information</p>
                        </div>
                        <form action="#">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">First Name</label>
                                        <input type="text" class="db-custom-input form-control" name="first-name" placeholder="Type here">
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Last Name</label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type here">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline custom-radio-btn">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Date of Birth </label>
                                        <input type="date" class="db-custom-input form-control" name="last-name" placeholder="Type here">
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Marital Status</label>
                                        <select class="db-custom-input form-control select-drop-down-arrow" id="">
                                            <option value="">Marital Status</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Nationality</label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type Nationality">
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Country</label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type Country">
                                    </div>
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">City</label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type City">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">City</label>
                                        <textarea rows="4" style="height: auto;" class="db-custom-input form-control" placeholder="Type City"></textarea>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Phone Number </label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type Phone">
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Email address</label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type Email">
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Identity Document</label>
                                        <select class="db-custom-input form-control select-drop-down-arrow" id="">
                                            <option value="">Name Document</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label class="db-label">Identity Document </label>
                                        <input type="text" class="db-custom-input form-control" name="last-name" placeholder="Type Number">
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                   2
                </div>
                <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                    3
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                   4
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('third_party_scripts')
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
