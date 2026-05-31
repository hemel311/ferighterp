<nav class="navbar admin-navbar navbar-expand bg-white">
    <div class="container-fluid px-3 px-lg-4">
        <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>
            <div class="dropdown">
                <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                    <span class="notification-dot"></span>
                    <i class="bi bi-bell" aria-hidden="true"></i>
                </button>
            </div>

            <div class="dropdown">
                <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="avatar-img avatar-sm" src="{{asset(\Illuminate\Support\Facades\Auth::guard('forwarder')->user()->image)}}" alt="Admin Hasan">
                    <span class="profile-name d-none d-sm-inline">Admin Hasan</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('commonlogout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>