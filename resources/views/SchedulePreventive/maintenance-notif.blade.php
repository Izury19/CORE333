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
      padding-bottom: 100px;
      overflow-x: hidden;
      background: #f4f6f8;
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
      z-index: 10;
    }
    .fc-day-today {
      background: none !important;
    }
    .status-badge {
      color: #fff;
      padding: 4px 10px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.9em;
    }
  </style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('maintenance-calendar');
    var currentClickedDate = null;
    var selectedScheduleId = null;

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
      },
      showNonCurrentDates: false,  
      fixedWeekCount: false,      

      events: function(fetchInfo, successCallback, failureCallback) {
        fetch('{{ route("calendar.events") }}')
          .then(response => response.json())
          .then(events => {
            const today = new Date();
            const transformedEvents = events.map(event => {
              let status = event.status.toLowerCase();
              if (status === 'pending' && new Date(event.start) < today) {
                status = 'overdue';
              }
              return { 
                ...event, 
                title: status.charAt(0).toUpperCase() + status.slice(1), 
                status: status,
                extendedProps: { ...event, status: status }
              };
            });
            successCallback(transformedEvents);
          })
          .catch(err => failureCallback(err));
      },

      eventDidMount: function(info) {
        let status = (info.event.status || info.event.extendedProps.status).toLowerCase();
        let color = status === 'pending' ? '#f0ad4e' :
                    status === 'completed' ? '#87CEEB' :
                    status === 'overdue' ? '#e74c3c' : '#6c757d';

        info.el.style.backgroundColor = color;
        info.el.style.borderColor = color;
        let tooltip = `${info.event.title}\nTechnician: ${info.event.extendedProps.technician}\nStatus: ${status}`;
        info.el.setAttribute("title", tooltip);
      },

      dateClick: function(info) {
        const clickedDate = info.dateStr;
        currentClickedDate = clickedDate;

        fetch('{{ route("calendar.events") }}')
          .then(response => response.json())
          .then(events => {
            const today = new Date();
            const matched = events.map(e => {
              let status = e.status.toLowerCase();
              if (status === 'pending' && new Date(e.start) < today) {
                status = 'overdue';
              }
              return { ...e, status };
            }).filter(e => e.start === clickedDate);

            function getStatusBadge(status) {
              let bgColor = status === 'pending' ? '#f0ad4e' :
                            status === 'completed' ? '#87CEEB' :
                            status === 'overdue' ? '#e74c3c' : '#6c757d';
              return `<span class="status-badge" style="background-color:${bgColor};">
                        ${status.charAt(0).toUpperCase() + status.slice(1)}
                      </span>`;
            }

            if (matched.length > 0) {
              let content = '<ul class="list-group">';
              matched.forEach(e => {
                content += `<li class="list-group-item" data-id="${e.id}">
                  <strong>${e.title}</strong><br>
                  Technician: ${e.technician}<br>
                  Email: <a href="mailto:${e.email}">${e.email}</a><br>
                  Status: ${getStatusBadge(e.status)}
                </li>`;
                selectedScheduleId = e.id; // pick first schedule ID
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
      },

      eventClick: function(info) {
        const clickedDate = info.event.startStr;
        currentClickedDate = clickedDate;
        selectedScheduleId = info.event.id; // assign schedule ID

        fetch('{{ route("calendar.events") }}')
          .then(response => response.json())
          .then(events => {
            const today = new Date();
            const matched = events.map(e => {
              let status = e.status.toLowerCase();
              if (status === 'pending' && new Date(e.start) < today) {
                status = 'overdue';
              }
              return { ...e, status };
            }).filter(e => e.start === clickedDate);

            function getStatusBadge(status) {
              let bgColor = status === 'pending' ? '#f0ad4e' :
                            status === 'completed' ? '#87CEEB' :
                            status === 'overdue' ? '#e74c3c' : '#6c757d';
              return `<span class="status-badge" style="background-color:${bgColor};">
                        ${status.charAt(0).toUpperCase() + status.slice(1)}
                      </span>`;
            }

            if (matched.length > 0) {
              let content = '<ul class="list-group">';
              matched.forEach(e => {
                content += `<li class="list-group-item" data-id="${e.id}">
                  <strong>${e.title}</strong><br>
                  Technician: ${e.technician}<br>
                  Email: <a href="mailto:${e.email}">${e.email}</a><br>
                  Status: ${getStatusBadge(e.status)}
                </li>`;
              });
              content += '</ul>';
              document.getElementById('modal-date').innerText = new Date(clickedDate).toDateString();
              document.getElementById('modal-body').innerHTML = content;
              new bootstrap.Modal(document.getElementById('scheduleModal')).show();
            }
          });
      }
    });

    calendar.render();

    // Send email notification
    document.getElementById('sendEmailBtn').addEventListener('click', function () {
      if (!currentClickedDate) return;
      Swal.fire({ title: 'Sending...', text: 'Please wait while emails are being sent.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
      fetch('/send-email-notification', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ date: currentClickedDate })
      })
      .then(res => res.json())
      .then(data => {
        Swal.close();
        Swal.fire({ icon: data.success ? 'success' : 'warning', title: data.message });
      })
      .catch(() => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong while sending email.' });
      });
    });

    // Upload Proof Button
    document.getElementById('uploadProofBtn').addEventListener('click', function () {
      if (!selectedScheduleId) {
        Swal.fire('No schedule selected', '', 'warning');
        return;
      }
      document.getElementById('schedule_id').value = selectedScheduleId;
      new bootstrap.Modal(document.getElementById('proofModal')).show();
    });

    // Proof Form Submit
    document.getElementById('proofForm').addEventListener('submit', function (e) {
      e.preventDefault();
      let formData = new FormData(this);
      fetch(`/maintenance/${selectedScheduleId}/upload-proof`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Success', data.message, 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      })
      .catch(() => Swal.fire('Error', 'Something went wrong.', 'error'));
    });
  });
</script>
@endpush


<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Maintenance Schedules on <span id="modal-date"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="sendEmailBtn">Send Email Notification</button>
        <button type="button" class="btn btn-warning" id="uploadProofBtn">Upload Proof</button>
      </div>
    </div>
  </div>
</div>

<!-- Upload Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Upload Proof of Completion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="proofForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
              <input type="hidden" id="schedule_id" name="schedule_id">
              <div class="mb-3">
                  <label for="proof" class="form-label">Upload Image</label>
                  <input type="file" class="form-control" name="proof_image" id="proof" accept="image/*" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success">Submit Proof</button>
          </div>
      </form>

    </div>
  </div>
</div>
