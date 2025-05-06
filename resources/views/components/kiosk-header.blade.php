@if(session('kiosk_company_id'))
<div class="kiosk-header">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center h-100">
            <h2 class="mb-0">{{ \App\Models\CompanyProfile::where('user_id', session('kiosk_company_id'))->first()->company_name ?? 'Company' }}</h2>
        </div>
    </div>
</div>
@endif 