
document.addEventListener('DOMContentLoaded', () => {

    let evenement = data;
    console.log(evenement)
    evenement = JSON.parse(evenement);


    let jsonEvent = [];
    evenement.forEach(element => {
        let jsonNewEvent = {
            id: element.id,
            title: element.title,
            'start': element.start.date,
            'end': element.end.date
        };
        jsonEvent.push(jsonNewEvent)
    });
    var calendarEl = document.getElementById('calendar-holder');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        defaultView: 'dayGridMonth',
        editable: true,
        eventDrop: (datas) => {
            if (!confirm("Êtes vous sûr de vouloir modifier la date de cet évenement ?")) {
                datas.revert()
            } else {
                let url = `/api/${datas.event.id
                    }/edit`
                console.log(datas.event.start);
                let donnees = {
                    "title": datas.event.title,
                    "start": datas.event.start,
                    "end": datas.event.end
                }
                let xhr = new XMLHttpRequest
                xhr.open("PUT", url)
                xhr.send(JSON.stringify(donnees))
            }
        },
        eventClick: (datas) => { },
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
});