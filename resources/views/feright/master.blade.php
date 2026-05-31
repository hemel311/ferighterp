<!DOCTYPE html>
<html lang="en">
<head>
    @include('feright.layout.meta')
    <title>@yield('title')|Atlantic Group</title>
    @include('feright.layout.css')
</head>

<body>
<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

   @include('feright.layout.aside')

    <div class="admin-main">
        @include('feright.layout.header')

        <main class="dashboard-content">
            @yield('body')
        </main>

       @include('feright.layout.footer')
    </div>
</div>

@include('feright.layout.js')
@stack('js')
</body>
</html>
