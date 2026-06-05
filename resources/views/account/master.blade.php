<!DOCTYPE html>
<html lang="en">
<head>
   @include('account.layout.meta')
    <title>@yield('title') | Atlantic Group</title>

    @include('account.layout.css')
</head>

<body>
<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    @include('account.layout.aside')

    <div class="admin-main">
       @include('account.layout.header')

        <main class="dashboard-content">
            @yield('body')
        </main>

       @include('account.layout.footer')
    </div>
</div>

@include('account.layout.js')
@stack('js')
<script>
    window.adminHMDUser = {
        name: "{{ Auth::guard('accountant')->user()->name }}",
        workspace: "{{ Auth::guard('accountant')->user()->designation ?? 'Administrator' }}",
        avatar: "{{asset(\Illuminate\Support\Facades\Auth::guard('accountant')->user()->image)}}"
    };
</script>
</body>
</html>
