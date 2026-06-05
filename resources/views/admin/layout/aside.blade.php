<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <a class="brand-mark" href="index.html" aria-label="adminHMD dashboard">
            <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
            <span class="brand-copy">
            <span class="brand-title">adminHMD</span>
            <span class="brand-subtitle">Admin Template</span>
          </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-link active" href="index.html" aria-current="page">
            <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        <div class="nav-item">

            <!-- Freight Menu -->
            <a class="nav-link" data-bs-toggle="collapse" href="#freightMenu" role="button">
        <span class="nav-icon">
            <i class="bi bi-truck"></i>
        </span>
                <span class="nav-text">Freight</span>
                <span class="ms-auto">
            <i class="bi bi-chevron-down"></i>
        </span>
            </a>

            <div class="collapse" id="freightMenu">

                <!-- Users Submenu -->
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#usersMenu" role="button">
            <span class="nav-icon">
                <i class="bi bi-people"></i>
            </span>
                    <span class="nav-text">Users</span>
                    <span class="ms-auto">
                <i class="bi bi-chevron-down"></i>
            </span>
                </a>

                <div class="collapse" id="usersMenu">

                    <a class="nav-link ps-5" href="{{route('addferight')}}">
                <span class="nav-icon">
                    <i class="bi bi-person-plus"></i>
                </span>
                        <span class="nav-text">Add Freight Forwarder</span>
                    </a>

                    <a class="nav-link ps-5" href="{{route('manage.feright')}}">
                <span class="nav-icon">
                    <i class="bi bi-list-ul"></i>
                </span>
                        <span class="nav-text">Manage Freight Forwarder</span>
                    </a>

                </div>
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#packinglist" role="button">
                    <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span>
                    <span class="nav-text">Packing List</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>
                <div class="collapse" id="packinglist">
                    <a class="nav-link ps-5 bi bi-file-earmark-text" href="{{route('trpl.index.admin')}}">
                        <span class="nav-text">See TR Packing List</span>
                    </a>
                    <a class="nav-link ps-5 bi bi-file-earmark-text" href="{{route('admin.us.pl')}}">
                        <span class="nav-text">See US Packing List</span>
                    </a>
                </div>
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#isf" role="button">
                    <span class="nav-icon"><i class="bi bi-file-earmark-check"></i></span>
                    <span class="nav-text">ISF</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>
                <div class="collapse" id="isf">
                    <a class="nav-link ps-5 bi bi-file-earmark-text" href="{{route('admin.isf.manage')}}">
                        <span class="nav-text">See ISF</span>
                    </a>
                    <a class="nav-link ps-5 bi bi-file-earmark-text" href="{{route('admin.isf.index')}}">
                        <span class="nav-text">Create ISF</span>
                    </a>

                </div>
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#si" role="button">
                    <span class="nav-icon"><i class="bi bi-file-earmark-richtext"></i></span>
                    <span class="nav-text">SI Excel</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>
                <div class="collapse" id="si">
                    <a class="nav-link ps-5 bi bi-file-earmark-text" href="#">
                        <span class="nav-text">See SI Excel</span>
                    </a>

                </div>
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#shipmentcompany" role="button">
                    <span class="nav-icon"><i class="bi bi-water"></i></span>
                    <span class="nav-text">MBL Prefix</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>
                <div class="collapse" id="shipmentcompany">
                    <a class="nav-link ps-5 bi bi-plus" href="{{route('addmblprefix')}}">
                        <span class="nav-text">Add MBL Prefix</span>
                    </a>

                    <a class="nav-link ps-5 bi bi-list-ul" href="{{route('manage.prefix')}}">
                        <span class="nav-text">Manage Prefix</span>
                    </a>
                </div>
                <a class="nav-link ps-4" href="{{route('addtemplate')}}">
                <span class="nav-icon">
                    <i class="bi bi-file-arrow-up"></i>
                </span>
                    <span class="nav-text">Upload Template</span>
                </a>
                <a class="nav-link ps-4" href="{{route('manage.templates')}}">
                <span class="nav-icon">
                    <i class="bi bi-list-ul"></i>
                </span>
                    <span class="nav-text">Manage Template</span>
                </a>
            </div>
        </div>
        <div class="nav-item">

            <!-- Accountant Menu -->
            <a class="nav-link" data-bs-toggle="collapse" href="#account" role="button">
        <span class="nav-icon">
            <i class="bi bi-currency-dollar"></i>
        </span>
                <span class="nav-text">Account</span>
                <span class="ms-auto">
            <i class="bi bi-chevron-down"></i>
        </span>
            </a>

            <div class="collapse" id="account">

                <!-- Users Submenu -->
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#usersMenuAccount" role="button">
            <span class="nav-icon">
                <i class="bi bi-people"></i>
            </span>
                    <span class="nav-text">Users</span>
                    <span class="ms-auto">
                <i class="bi bi-chevron-down"></i>
            </span>
                </a>

                <div class="collapse" id="usersMenuAccount">

                    <a class="nav-link ps-5" href="{{route('addaccount')}}">
                <span class="nav-icon">
                    <i class="bi bi-person-plus"></i>
                </span>
                        <span class="nav-text">Add Accountant</span>
                    </a>

                    <a class="nav-link ps-5" href="{{route('manage.accountant')}}">
                <span class="nav-icon">
                    <i class="bi bi-list-ul"></i>
                </span>
                        <span class="nav-text">Manage Accountant</span>
                    </a>

                </div>
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#calculation" role="button">
                    <span class="nav-icon"><i class="bi bi-calculator"></i></span>
                    <span class="nav-text">Calculation</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>
                <div class="collapse" id="calculation">
                    <a class="nav-link ps-5 bi bi-calculator" href="#">
                        <span class="nav-text">See Calculation</span>
                    </a>
                </div>
                <a class="nav-link ps-4" data-bs-toggle="collapse" href="#cinvoice" role="button">
                    <span class="nav-icon"><i class="bi bi-receipt"></i></span>
                    <span class="nav-text">Commercial Invoice</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down"></i></span>
                </a>
                <div class="collapse" id="cinvoice">
                    <a class="nav-link ps-5 bi bi-receipt" href="#">
                        <span class="nav-text">See Commercial Invoice</span>
                    </a>
                </div>
                <a class="nav-link ps-4" href="{{route('addtemplate')}}">
                <span class="nav-icon">
                    <i class="bi bi-file-arrow-up"></i>
                </span>
                    <span class="nav-text">Upload Template</span>
                </a>
                <a class="nav-link ps-4" href="{{route('manage.templates')}}">
                <span class="nav-icon">
                    <i class="bi bi-list-ul"></i>
                </span>
                    <span class="nav-text">Manage Template</span>
                </a>
                {{--<a class="nav-link ps-4" data-bs-toggle="collapse" href="#si" role="button">--}}
                    {{--<span class="nav-icon"><i class="bi bi-file-earmark-richtext"></i></span>--}}
                    {{--<span class="nav-text">SI Excel</span>--}}
                    {{--<span class="ms-auto"><i class="bi bi-chevron-down"></i></span>--}}
                {{--</a>--}}
                {{--<div class="collapse" id="si">--}}
                    {{--<a class="nav-link ps-5 bi bi-file-earmark-text" href="#">--}}
                        {{--<span class="nav-text">See SI Excel</span>--}}
                    {{--</a>--}}

                    {{--<a class="nav-link ps-5 bi bi-file-arrow-up" href="#">--}}
                        {{--<span class="nav-text">Upload Templates</span>--}}
                    {{--</a>--}}
                {{--</div>--}}
                {{--<a class="nav-link ps-4" data-bs-toggle="collapse" href="#shipmentcompany" role="button">--}}
                    {{--<span class="nav-icon"><i class="bi bi-water"></i></span>--}}
                    {{--<span class="nav-text">MBL Prefix</span>--}}
                    {{--<span class="ms-auto"><i class="bi bi-chevron-down"></i></span>--}}
                {{--</a>--}}
                {{--<div class="collapse" id="shipmentcompany">--}}
                    {{--<a class="nav-link ps-5 bi bi-plus" href="#">--}}
                        {{--<span class="nav-text">Add MBL Prefix</span>--}}
                    {{--</a>--}}

                    {{--<a class="nav-link ps-5 bi bi-list-ul" href="#">--}}
                        {{--<span class="nav-text">Manage Prefix</span>--}}
                    {{--</a>--}}
                {{--</div>--}}
            </div>

        </div>
    </nav>

    <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="{{asset('assets')}}/images/avatar/avatar-fallback.jpg" alt="Admin Hasan">
        <strong>{{\Illuminate\Support\Facades\Auth::guard('admin')->user()->name}}</strong>
        <small>Administrator</small>
    </div>

    {{--<div class="sidebar-footer">--}}
        {{--<span class="status-dot"></span>--}}
        {{--<span class="sidebar-footer-text">System running smoothly</span>--}}
    {{--</div>--}}
</aside>