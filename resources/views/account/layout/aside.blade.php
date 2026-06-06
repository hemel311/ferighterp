<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <a class="brand-mark" href="{{route('account.dashboard')}}" aria-label="adminHMD dashboard">
            <span class="brand-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
            <span class="brand-copy">
            <span class="brand-title">Atlantic Group</span>
            <span class="brand-subtitle">Freight ERP</span>
          </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-link active" href="{{route('account.dashboard')}}" aria-current="page">
            <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        {{--Calculation menu--}}
        <a class="nav-link" data-bs-toggle="collapse" href="#calculation">
    <span class="nav-icon">
        <i class="bi bi-calculator"></i>
    </span>
            <span class="nav-text">Calculation</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="calculation">
            <a class="nav-link ps-5" href="{{route('account.calculation.create')}}">Create Calculation</a>
            <a class="nav-link ps-5" href="{{route('account.calculation.index')}}">Manage Calculation</a>
        </div>

        <!-- Shipment -->
        <a class="nav-link" data-bs-toggle="collapse" href="#commercialInvoice">
    <span class="nav-icon">
        <i class="bi bi-receipt"></i>
    </span>
            <span class="nav-text">Commercial Invoice</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="commercialInvoice">
            <a class="nav-link ps-5" href="{{route('account.commercial.create')}}">Create Commercial Invoice</a>
            <a class="nav-link ps-5" href="{{route('account.commercial.index')}}">Manage Commercial Invoice</a>
        </div>
    </nav>

    <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{asset(\Illuminate\Support\Facades\Auth::guard('accountant')->user()->image)}}" alt="Admin Hasan">
        <strong>{{\Illuminate\Support\Facades\Auth::guard('accountant')->user()->name}}</strong>
        <small>Account</small>
    </div>

    {{--<div class="sidebar-footer">--}}
    {{--<span class="status-dot"></span>--}}
    {{--<span class="sidebar-footer-text">System running smoothly</span>--}}
    {{--</div>--}}
</aside>