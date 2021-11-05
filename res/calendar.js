document.addEventListener('DOMContentLoaded', () => {
    if (data) {
        let evenement = '{{ data | raw }}'
        evenement = JSON.parse(evenement)
        let jsonEvent = [{
            id: evenement[0]['id'],
            title: evenement[0]['title'],
            'start': evenement[0]['start'].date,
            'end': evenement[0]['end'].date
        }];
        console.log(jsonEvent)
        var calendarEl = document.getElementById('calendar-holder');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            defaultView: 'dayGridMonth',
            editable: true,
            eventSources: [
                {
                    url: "{{ path('main') }}",
                    method: "POST",
                    extraParams: {
                        filters: JSON.stringify({})
                    },
                    failure: () => { // alert("There was an error while fetching FullCalendar!");
                    }
                },
            ],
            header: {
                left: 'prev next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Aujourd\'hui',
                day: 'Jour',
                month: 'Mois',
                week: 'Semaine'
            },
            plugins: [
                'interaction', 'dayGrid', 'timeGrid'
            ], // https://fullcalendar.io/docs/plugin-index
            locale: 'fr',
            events: jsonEvent,
            timeZone: 'Europe/Paris'
        });
        calendar.render();
    }
});