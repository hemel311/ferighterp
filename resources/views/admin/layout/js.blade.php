<script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('assets')}}/js/main.js"></script>
{{--notify3--}}
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script>
    const notyf = new Notyf({
        duration: 3000,
        position: { x: 'right', y: 'top' }
    });

    @if (session('success'))
    notyf.success("{{ session('success') }}");
    @endif

    @if (session('error'))
    notyf.error("{{ session('error') }}");
    @endif
</script>