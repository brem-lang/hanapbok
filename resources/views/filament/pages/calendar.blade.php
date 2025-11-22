<x-filament-panels::page>
    <div id="calendar"
        class="filament-stats-card relative p-6 rounded-2xl bg-white shadow dark:bg-gray-800 filament-stats-overview-widget-card">
    </div>
</x-filament-panels::page>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

<script>
    var $loading = $('.sk-chase').hide();
    $(document)
        .ajaxStart(function() {
            $loading.show();
        })
        .ajaxStop(function() {
            $loading.hide();
        });

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        selectable: true,
        events: @json($this->calendarEvents),
        //         eventMouseEnter: function(data) {

        //             let tooltipContent = `
        //     <div>
        //         <h1 style="color: ${data.event.extendedProps.status === 'Pending' ? 'gray' : (data.event.extendedProps.status === 'Approved' ? '#50C878' : 'red') }"> 
        //             <strong> ${data.event.extendedProps.status} </strong> 
        //         </h1>
        //         <div><strong>Request Type:</strong> <br> <p style="padding-left: 15px"> - ${data.event.extendedProps.type} </p> </div>
        //         <div><strong>Reason or Description:</strong><br> <p style="padding-left: 15px"> - ${data.event.extendedProps.reason} </p></div>
        //         <div><strong>Date and Time:</strong><br> <p style="padding-left: 15px"> - ${data.event.extendedProps.date_time} </p></div>
        //         <div><strong>Submitted at:</strong><br> <p style="padding-left: 15px"> - ${data.event.extendedProps.submitted_at} </p></div>
        //     </div>
        // `;
        //             let tooltip = tippy(data.el, {
        //                 content: tooltipContent,
        //                 placement: "top",
        //                 interactive: true,
        //                 arrow: true,
        //                 theme: "material",
        //                 appendTo: document.body,
        //                 allowHTML: true,
        //                 duration: [1, 1],
        //                 animation: "scale-extreme",
        //             });
        //         },
        // eventClick: function(data) {
        //     var url = window.location.href;

        //     var link = url.replace("/calendar", "") + "/" + data.event['extendedProps']['linkType'] + "/" +
        //         data
        //         .event['_def']['publicId'];

        //     window.open(link, '_blank');
        // },
    });

    calendar.render();
</script>
