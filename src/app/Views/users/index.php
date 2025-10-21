<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>User Management - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">User Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Users</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>All Users</h5>
                <div class="btn-group">
                    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-user-plus"></use>
                        </svg>
                        Add User
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
                    <table id="usersTable" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Created</th>
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

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="userDetails">
                    <!-- User details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editUser()">Edit User</button>
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
let usersTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    usersTable = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('api/users') ?>',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '60px' },
            { 
                data: 'avatar', 
                name: 'avatar',
                orderable: false,
                searchable: false,
                width: '80px',
                render: function(data, type, row) {
                    return `<img src="${data || '<?= base_url('assets/images/user/avatar-1.jpg') ?>'}" 
                                alt="avatar" class="rounded-circle" width="40" height="40">`;
                }
            },
            { data: 'full_name', name: 'full_name' },
            { data: 'email', name: 'email' },
            { 
                data: 'role', 
                name: 'role',
                render: function(data, type, row) {
                    const roleColors = {
                        'admin': 'danger',
                        'teacher': 'primary', 
                        'student': 'success'
                    };
                    return `<span class="badge bg-${roleColors[data] || 'secondary'}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    return `<span class="badge bg-${data === 'active' ? 'success' : 'danger'}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            { 
                data: 'last_login', 
                name: 'last_login',
                render: function(data, type, row) {
                    return data ? new Date(data).toLocaleDateString() : 'Never';
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data, type, row) {
                    return new Date(data).toLocaleDateString();
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
                            <button class="btn btn-outline-primary" onclick="viewUser(${data})" title="View">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-eye"></use>
                                </svg>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="editUser(${data})" title="Edit">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-edit"></use>
                                </svg>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteUser(${data})" title="Delete">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-trash"></use>
                                </svg>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']],
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
});

function getToken() {
    // Get token from localStorage or session
    return localStorage.getItem('access_token') || '';
}

function refreshTable() {
    usersTable.ajax.reload();
}

function viewUser(id) {
    // Load user details via AJAX
    fetch(`<?= base_url('api/users/') ?>${id}`, {
        headers: {
            'Authorization': 'Bearer ' + getToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.data;
            document.getElementById('userDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="${user.avatar || '<?= base_url('assets/images/user/avatar-1.jpg') ?>'}" 
                             alt="avatar" class="rounded-circle mb-3" width="100" height="100">
                        <h5>${user.full_name}</h5>
                        <span class="badge bg-${user.role === 'admin' ? 'danger' : (user.role === 'teacher' ? 'primary' : 'success')}">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</span>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr><td><strong>Email:</strong></td><td>${user.email}</td></tr>
                            <tr><td><strong>Status:</strong></td><td><span class="badge bg-${user.status === 'active' ? 'success' : 'danger'}">${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</span></td></tr>
                            <tr><td><strong>Last Login:</strong></td><td>${user.last_login ? new Date(user.last_login).toLocaleString() : 'Never'}</td></tr>
                            <tr><td><strong>Created:</strong></td><td>${new Date(user.created_at).toLocaleString()}</td></tr>
                        </table>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }
    })
    .catch(error => console.error('Error:', error));
}

function editUser(id) {
    window.location.href = `<?= base_url('users/') ?>${id}/edit`;
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`<?= base_url('api/users/') ?>${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                usersTable.ajax.reload();
                // Show success message
                showNotification('User deleted successfully', 'success');
            } else {
                showNotification('Error deleting user: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting user', 'error');
        });
    }
}

function showNotification(message, type) {
    // Simple notification - you can enhance this with a proper notification system
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alert = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    
    // Insert at top of page
    document.querySelector('.page-header').insertAdjacentHTML('afterend', alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            alertElement.remove();
        }
    }, 5000);
}
</script>
<?= $this->endSection() ?>
