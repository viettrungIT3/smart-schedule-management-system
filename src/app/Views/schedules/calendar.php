<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Schedule Calendar - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Schedule Calendar</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('schedules') ?>">Schedules</a></li>
                    <li class="breadcrumb-item">Calendar</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Controls -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="calendar.changeView('dayGridMonth')">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-calendar"></use>
                                </svg>
                                Month
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="calendar.changeView('timeGridWeek')">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-calendar"></use>
                                </svg>
                                Week
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="calendar.changeView('timeGridDay')">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-calendar"></use>
                                </svg>
                                Day
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="calendar.changeView('listWeek')">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-list"></use>
                                </svg>
                                List
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary" onclick="calendar.prev()">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-chevron-left"></use>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="calendar.today()">
                                Today
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="calendar.next()">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-chevron-right"></use>
                                </svg>
                            </button>
                        </div>
                        <a href="<?= base_url('schedules/create') ?>" class="btn btn-primary ms-2">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-calendar-plus"></use>
                            </svg>
                            Add Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Schedule Calendar</h5>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Schedule Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="scheduleDetails">
                    <!-- Schedule details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editSchedule()" id="editScheduleBtn">Edit Schedule</button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Add New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="teacher_id" class="form-label">Teacher</label>
                            <select class="form-select" id="teacher_id" name="teacher_id" required>
                                <option value="">Select Teacher</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_id" class="form-label">Room</label>
                            <select class="form-select" id="room_id" name="room_id" required>
                                <option value="">Select Room</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="schedule_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="schedule_date" name="schedule_date" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSchedule()">Save Schedule</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
.fc {
    font-family: inherit;
}

.fc-toolbar-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.fc-button {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.fc-button:hover {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    opacity: 0.8;
}

.fc-button:focus {
    box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
}

.fc-button-active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.fc-event {
    border-radius: 4px;
    border: none;
    padding: 2px 4px;
    font-size: 0.85rem;
}

.fc-event-title {
    font-weight: 500;
}

.fc-daygrid-event {
    margin: 1px 0;
}

.fc-timegrid-event {
    border-radius: 4px;
}

.fc-popover {
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.fc-popover-header {
    background-color: var(--bs-light);
    border-bottom: 1px solid var(--bs-border-color);
}

.fc-popover-body {
    padding: 1rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
let calendar;
let currentScheduleId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false, // We're using custom controls
        height: 'auto',
        events: {
            url: '<?= base_url('api/schedules/calendar') ?>',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            },
            failure: function() {
                showNotification('Failed to load schedule events', 'error');
            }
        },
        eventClick: function(info) {
            viewSchedule(info.event.id);
        },
        dateClick: function(info) {
            addSchedule(info.dateStr);
        },
        eventDrop: function(info) {
            updateScheduleDate(info.event.id, info.event.start);
        },
        eventResize: function(info) {
            updateScheduleTime(info.event.id, info.event.start, info.event.end);
        },
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
            startTime: '08:00',
            endTime: '18:00'
        },
        eventColor: function(info) {
            // Color events based on status
            const status = info.event.extendedProps.status;
            switch(status) {
                case 'active': return '#28a745';
                case 'completed': return '#007bff';
                case 'cancelled': return '#dc3545';
                default: return '#6c757d';
            }
        },
        eventTextColor: '#ffffff',
        eventDisplay: 'block',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });
    
    calendar.render();
    
    // Load form options
    loadFormOptions();
});

function getToken() {
    return localStorage.getItem('access_token') || '';
}

function loadFormOptions() {
    // Load subjects
    fetch('<?= base_url('api/subjects') ?>', {
        headers: {
            'Authorization': 'Bearer ' + getToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const subjectSelect = document.getElementById('subject_id');
            data.data.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });
        }
    });
    
    // Load teachers
    fetch('<?= base_url('api/users?role=teacher') ?>', {
        headers: {
            'Authorization': 'Bearer ' + getToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const teacherSelect = document.getElementById('teacher_id');
            data.data.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = teacher.full_name;
                teacherSelect.appendChild(option);
            });
        }
    });
    
    // Load rooms
    fetch('<?= base_url('api/rooms') ?>', {
        headers: {
            'Authorization': 'Bearer ' + getToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const roomSelect = document.getElementById('room_id');
            data.data.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.name;
                roomSelect.appendChild(option);
            });
        }
    });
}

function viewSchedule(scheduleId) {
    fetch(`<?= base_url('api/schedules/') ?>${scheduleId}`, {
        headers: {
            'Authorization': 'Bearer ' + getToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const schedule = data.data;
            document.getElementById('scheduleDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Schedule Information</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>Subject:</strong></td><td>${schedule.subject_name}</td></tr>
                            <tr><td><strong>Teacher:</strong></td><td>${schedule.teacher_name}</td></tr>
                            <tr><td><strong>Room:</strong></td><td>${schedule.room_name}</td></tr>
                            <tr><td><strong>Date:</strong></td><td>${new Date(schedule.schedule_date).toLocaleDateString()}</td></tr>
                            <tr><td><strong>Time:</strong></td><td>${schedule.start_time} - ${schedule.end_time}</td></tr>
                            <tr><td><strong>Duration:</strong></td><td>${schedule.duration} minutes</td></tr>
                            <tr><td><strong>Status:</strong></td><td><span class="badge bg-${schedule.status === 'active' ? 'success' : (schedule.status === 'completed' ? 'primary' : 'danger')}">${schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1)}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Additional Details</h6>
                        <p><strong>Description:</strong></p>
                        <p>${schedule.description || 'No description provided'}</p>
                        <p><strong>Created:</strong> ${new Date(schedule.created_at).toLocaleString()}</p>
                        <p><strong>Updated:</strong> ${new Date(schedule.updated_at).toLocaleString()}</p>
                    </div>
                </div>
            `;
            currentScheduleId = scheduleId;
            new bootstrap.Modal(document.getElementById('scheduleModal')).show();
        }
    })
    .catch(error => console.error('Error:', error));
}

function editSchedule() {
    if (currentScheduleId) {
        window.location.href = `<?= base_url('schedules/') ?>${currentScheduleId}/edit`;
    }
}

function addSchedule(date) {
    document.getElementById('schedule_date').value = date;
    document.getElementById('addScheduleModalLabel').textContent = 'Add New Schedule';
    document.getElementById('scheduleForm').reset();
    document.getElementById('schedule_date').value = date;
    new bootstrap.Modal(document.getElementById('addScheduleModal')).show();
}

function saveSchedule() {
    const formData = new FormData(document.getElementById('scheduleForm'));
    const data = Object.fromEntries(formData);
    
    fetch('<?= base_url('api/schedules') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getToken()
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            calendar.refetchEvents();
            bootstrap.Modal.getInstance(document.getElementById('addScheduleModal')).hide();
            showNotification('Schedule created successfully', 'success');
        } else {
            showNotification('Error creating schedule: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error creating schedule', 'error');
    });
}

function updateScheduleDate(scheduleId, newDate) {
    fetch(`<?= base_url('api/schedules/') ?>${scheduleId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getToken()
        },
        body: JSON.stringify({
            schedule_date: newDate.toISOString().split('T')[0]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Schedule updated successfully', 'success');
        } else {
            showNotification('Error updating schedule: ' + data.message, 'error');
            calendar.refetchEvents(); // Revert the change
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating schedule', 'error');
        calendar.refetchEvents(); // Revert the change
    });
}

function updateScheduleTime(scheduleId, startTime, endTime) {
    fetch(`<?= base_url('api/schedules/') ?>${scheduleId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getToken()
        },
        body: JSON.stringify({
            start_time: startTime.toTimeString().split(' ')[0].substring(0, 5),
            end_time: endTime.toTimeString().split(' ')[0].substring(0, 5)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Schedule updated successfully', 'success');
        } else {
            showNotification('Error updating schedule: ' + data.message, 'error');
            calendar.refetchEvents(); // Revert the change
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating schedule', 'error');
        calendar.refetchEvents(); // Revert the change
    });
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alert = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    
    document.querySelector('.page-header').insertAdjacentHTML('afterend', alert);
    
    setTimeout(() => {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            alertElement.remove();
        }
    }, 5000);
}
</script>
<?= $this->endSection() ?>
