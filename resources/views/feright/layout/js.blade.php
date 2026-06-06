<script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('assets')}}/js/main.js"></script>
{{--calender--}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
{{--notify3--}}
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{--select2--}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 650,

            events: "{{ route('forwarder.calendar.events') }}"
        });

        calendar.render();
    });
</script>
{{--calender script--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 650,

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },

            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                list: 'List'
            },

            events: '{{route('forwarder.calendar.events')}}'
        });
        calendar.render();
    });
</script>