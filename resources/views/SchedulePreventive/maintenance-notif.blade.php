@extends('layouts.maintenance')

@section('content')
  <div class="container mt-4">
    <h2 class="text-center">🛠 Maintenance Notification Calendar</h2>
    <div id="maintenance-calendar" style="height: 600px;"></div>
  </div>

  <style>
    #maintenance-calendar {
      max-width: 1000px;
      margin: 20px auto;
      background: white;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    body {
      padding-bottom: 60px;
    }
    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      height: 60px;
      background-color: #000;
      color: white;
      text-align: center;
      line-height: 60px;
      z-index: 1000;
    }
    .fc-day-today {
      background: none !important;
    }

  </style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('maintenance-calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
      },
      
      // Gamitin ito para i-fetch at i-transform ang events bago i-display
      events: function(fetchInfo, successCallback, failureCallback) {
        fetch('{{ route("calendar.events") }}')
          .then(response => response.json())
          .then(events => {
            const transformedEvents = events.map(event => {
              let newTitle = '';
              if (event.status.toLowerCase() === 'pending') {
                newTitle = 'Pending';
              } else if (event.status.toLowerCase() === 'completed') {
                newTitle = 'Completed';
              } else if (event.status.toLowerCase() === 'overdue') {
                newTitle = 'Overdue';
              } else {
                newTitle = event.title; // fallback
              }

              return {
                ...event,
                title: newTitle
              };
            });
            successCallback(transformedEvents);
          })
          .catch(err => failureCallback(err));
      },

      eventDidMount: function(info) {
        let status = info.event.extendedProps.status.toLowerCase();
        let color = '';

        if (status === 'pending') {
          color = '#f0ad4e';  // orange
        } else if (status === 'completed') {
          color = '#87CEEB';  // sky blue
        } else if (status === 'overdue') {
          color = '#e74c3c';  // red
        } else {
          color = '#6c757d';  // default gray
        }

        info.el.style.backgroundColor = color;
        info.el.style.borderColor = color;

        let tooltip = `${info.event.title}\nTechnician: ${info.event.extendedProps.technician}\nStatus: ${info.event.extendedProps.status}`;
        info.el.setAttribute("title", tooltip);
      },

      dayCellClassNames: function(arg) {
        const todayStr = new Date().toISOString().split('T')[0];
        if (arg.date.toISOString().split('T')[0] === todayStr) {
          return [];
        }
        return [];
      },

      dateClick: function(info) {
        const clickedDate = info.dateStr;

        fetch('{{ route("calendar.events") }}')
          .then(response => response.json())
          .then(events => {
            const matched = events.filter(e => e.start === clickedDate);

            function getStatusBadge(status) {
              status = status.toLowerCase();
              let bgColor = '', textColor = '#fff';

              if (status === 'pending') {
                bgColor = '#f0ad4e'; // orange
              } else if (status === 'completed') {
                bgColor = '#87CEEB'; // sky blue
              } else if (status === 'overdue') {
                bgColor = '#e74c3c'; // red
              } else {
                bgColor = '#6c757d'; // gray default
              }

              return `<span style="background-color:${bgColor}; color:${textColor}; padding:4px 10px; border-radius:20px; font-weight:600; font-size:0.9em;">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
            }

            if (matched.length > 0) {
              let content = '<ul class="list-group">';
              matched.forEach(e => {
                content += `<li class="list-group-item">
                  <strong>${e.title}</strong><br>
                  Technician: ${e.technician}<br>
                  Status: ${getStatusBadge(e.status)}
                </li>`;
              });
              content += '</ul>';
              document.getElementById('modal-date').innerText = new Date(clickedDate).toDateString();
              document.getElementById('modal-body').innerHTML = content;
              new bootstrap.Modal(document.getElementById('scheduleModal')).show();
            } else {
              document.getElementById('modal-date').innerText = new Date(clickedDate).toDateString();
              document.getElementById('modal-body').innerHTML = "<p class='text-muted'>No schedules for this date.</p>";
              new bootstrap.Modal(document.getElementById('scheduleModal')).show();
            }
          });
      }
    });

    calendar.render();
  });
</script>
@endpush



<!-- Bootstrap Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="scheduleModalLabel">Maintenance Schedules on <span id="modal-date"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-body">
        <!-- Schedule details inserted here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
