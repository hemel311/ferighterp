<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <a class="brand-mark" href="{{route('feright.dashboard')}}" aria-label="adminHMD dashboard">
            <span class="brand-icon"><i class="bi bi-truck" aria-hidden="true"></i></span>
            <span class="brand-copy">
            <span class="brand-title">Atlantic Group</span>
            <span class="brand-subtitle">Freight ERP</span>
          </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-link active" href="{{route('feright.dashboard')}}" aria-current="page">
            <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        {{--Product menu--}}
        <a class="nav-link" data-bs-toggle="collapse" href="#productMenu">
    <span class="nav-icon">
        <i class="bi bi-tag"></i>
    </span>
            <span class="nav-text">Product</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="productMenu">
            <a class="nav-link ps-5" href="{{route('add.product')}}">Add Product</a>
            <a class="nav-link ps-5" href="{{route('manage.product')}}">Manage Product</a>
        </div>

        <!-- Shipment -->
        <a class="nav-link" data-bs-toggle="collapse" href="#shipmentMenu">
    <span class="nav-icon">
        <i class="bi bi-truck"></i>
    </span>
            <span class="nav-text">Shipment</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="shipmentMenu">
            <a class="nav-link ps-5" href="{{route('add.shipment')}}">Create Shipment</a>
            <a class="nav-link ps-5" href="{{route('manage.shipment')}}">Manage Shipment</a>
        </div>

        <!-- Container Info -->
        <a class="nav-link" data-bs-toggle="collapse" href="#containerMenu">
    <span class="nav-icon">
        <i class="bi bi-box-seam"></i>
    </span>
            <span class="nav-text">Container Info</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="containerMenu">
            <a class="nav-link ps-5" href="{{route('add.container')}}">Add Container</a>
            <a class="nav-link ps-5" href="{{route('container.manage')}}">Manage Container</a>
        </div>
        {{--vgm info--}}
        <a class="nav-link" data-bs-toggle="collapse" href="#vgmMenu">
    <span class="nav-icon">
        <i class="bi bi-speedometer2"></i>
    </span>
            <span class="nav-text">VGM Info</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="vgmMenu">
            <a class="nav-link ps-5" href="{{route('vgm')}}">Add VGM Info</a>
        </div>
        <!-- Packing List -->
        <a class="nav-link" data-bs-toggle="collapse" href="#packingMenu">
    <span class="nav-icon">
        <i class="bi bi-file-earmark-text"></i>
    </span>
            <span class="nav-text">Packing List</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="packingMenu">
            <a class="nav-link ps-5" href="{{route('trpl.index')}}">Add TR PL</a>
            <a class="nav-link ps-5" href="{{route('us.pl')}}">Add us PL</a>
        </div>

        <!-- SI Excel -->
        <a class="nav-link" data-bs-toggle="collapse" href="#siMenu">
    <span class="nav-icon">
        <i class="bi bi-file-earmark-spreadsheet"></i>
    </span>
            <span class="nav-text">SI Excel</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="siMenu">
            <a class="nav-link ps-5" href="#">Add SI Excel</a>
            <a class="nav-link ps-5" href="#">Manage SI Excel</a>
        </div>

        <!-- ISF -->
        <a class="nav-link" data-bs-toggle="collapse" href="#isfMenu">
    <span class="nav-icon">
        <i class="bi bi-shield-check"></i>
    </span>
            <span class="nav-text">ISF</span>
            <span class="ms-auto">
        <i class="bi bi-chevron-down"></i>
    </span>
        </a>

        <div class="collapse" id="isfMenu">
            <a class="nav-link ps-5" href="{{route('isf.index')}}">Add ISF</a>
            <a class="nav-link ps-5" href="{{route('isf.manage')}}">Manage ISF</a>
        </div>
    </nav>

    <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{asset(\Illuminate\Support\Facades\Auth::guard('forwarder')->user()->image)}}" alt="Admin Hasan">
        <strong>{{\Illuminate\Support\Facades\Auth::guard('forwarder')->user()->name}}</strong>
        <small>Freight</small>
    </div>

    {{--<div class="sidebar-footer">--}}
    {{--<span class="status-dot"></span>--}}
    {{--<span class="sidebar-footer-text">System running smoothly</span>--}}
    {{--</div>--}}
</aside>