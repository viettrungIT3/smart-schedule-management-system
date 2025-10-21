<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Schedule Management - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Schedule Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Schedules</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To</label>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="applyFilters()">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-search"></use>
                                </svg>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedules Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>All Schedules</h5>
                <div class="btn-group">
                    <a href="<?= base_url('schedules/create') ?>" class="btn btn-primary">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-calendar-plus"></use>
                        </svg>
                        Add Schedule
                    </a>
                    <button class="btn btn-outline-secondary" onclick="refreshTable()">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-refresh"></use>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="schedulesTable" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Room</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Details Modal -->
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
                <button type="button" class="btn btn-primary" onclick="editSchedule()">Edit Schedule</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/ablepro/css/plugins/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/ablepro/css/plugins/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/ablepro/css/plugins/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- DataTables JS -->
<script src="<?= base_url('assets/ablepro/js/plugins/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/buttons.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('assets/ablepro/js/plugins/responsive.bootstrap5.min.js') ?>"></script>

<script>
let schedulesTable;

document.addEventListener('DOMContentLoaded', function() {
    // Set default date range (current month)
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
    document.getElementById('endDate').value = lastDay.toISOString().split('T')[0];
    
    // Initialize DataTable
    initializeTable();
});

function initializeTable() {
    schedulesTable = $('#schedulesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('api/schedules') ?>',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            },
            data: function(d) {
                d.start_date = document.getElementById('startDate').value;
                d.end_date = document.getElementById('endDate').value;
                d.status = document.getElementById('statusFilter').value;
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '60px' },
            { data: 'subject_name', name: 'subject_name' },
            { data: 'teacher_name', name: 'teacher_name' },
            { data: 'room_name', name: 'room_name' },
            { 
                data: 'schedule_date', 
                name: 'schedule_date',
                render: function(data, type, row) {
                    return new Date(data).toLocaleDateString();
                }
            },
            { 
                data: 'start_time', 
                name: 'start_time',
                render: function(data, type, row) {
                    return data + ' - ' + row.end_time;
                }
            },
            { 
                data: 'duration', 
                name: 'duration',
                render: function(data, type, row) {
                    return data + ' minutes';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    const statusColors = {
                        'active': 'success',
                        'completed': 'primary',
                        'cancelled': 'danger'
                    };
                    return `<span class="badge bg-${statusColors[data] || 'secondary'}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            { 
                data: 'id', 
                name: 'actions',
                orderable: false,
                searchable: false,
                width: '120px',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="viewSchedule(${data})" title="View">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-eye"></use>
                                </svg>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="editSchedule(${data})" title="Edit">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-edit"></use>
                                </svg>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteSchedule(${data})" title="Delete">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-trash"></use>
                                </svg>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[4, 'desc']], // Sort by date
        pageLength: 25,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-outline-secondary btn-sm'
            },
            {
                extend: 'csv',
                className: 'btn btn-outline-secondary btn-sm'
            },
            {
                extend: 'excel',
                className: 'btn btn-outline-success btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm'
            },
            {
                extend: 'print',
                className: 'btn btn-outline-info btn-sm'
            },
            {
                extend: 'colvis',
                className: 'btn btn-outline-warning btn-sm'
            }
        ],
        language: {
            processing: "Loading...",
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
}

function getToken() {
    return localStorage.getItem('access_token') || '';
}

function applyFilters() {
    schedulesTable.ajax.reload();
}

function refreshTable() {
    schedulesTable.ajax.reload();
}

function viewSchedule(id) {
    fetch(`<?= base_url('api/schedules/') ?>${id}`, {
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
            new bootstrap.Modal(document.getElementById('scheduleModal')).show();
        }
    })
    .catch(error => console.error('Error:', error));
}

function editSchedule(id) {
    window.location.href = `<?= base_url('schedules/') ?>${id}/edit`;
}

function deleteSchedule(id) {
    if (confirm('Are you sure you want to delete this schedule?')) {
        fetch(`<?= base_url('api/schedules/') ?>${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                schedulesTable.ajax.reload();
                showNotification('Schedule deleted successfully', 'success');
            } else {
                showNotification('Error deleting schedule: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting schedule', 'error');
        });
    }
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
