<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layout.meta')
    <title>@yield('title')</title>

    @include('admin.layout.css')
</head>

<body>
<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    @include('admin.layout.aside')

    <div class="admin-main">
        @include('admin.layout.nav')

        <main class="dashboard-content">
            @yield('body')
        </main>

        <footer class="admin-footer">
            @include('admin.layout.footer')
        </footer>
    </div>
</div>
@include('admin.layout.js')
@stack('js')
<script>
    window.adminHMDUser = {
        name: "{{ Auth::guard('admin')->user()->name }}",
        workspace: "{{ Auth::guard('admin')->user()->designation ?? 'Administrator' }}",
        avatar: "{{ asset('assets/images/avatar/avatar-fallback.jpg') }}"
    };
</script>
</body>
</html>
