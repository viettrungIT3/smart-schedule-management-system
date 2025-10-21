<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Admin Dashboard - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Admin Dashboard</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Admin</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-widget">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-widget-icon bg-primary">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-users"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Total Users</h6>
                        <h4 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card stat-widget">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-widget-icon bg-success">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-calendar"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Active Schedules</h6>
                        <h4 class="mb-0"><?= $stats['total_schedules'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card stat-widget">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-widget-icon bg-info">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-building"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Classes</h6>
                        <h4 class="mb-0"><?= $stats['total_classes'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card stat-widget">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stat-widget-icon bg-warning">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-clipboard-check"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Avg Attendance</h6>
                        <h4 class="mb-0"><?= $stats['attendance_rate'] ?? 0 ?>%</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="row">
    <!-- System Overview Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5>System Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="dashboardChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/users/create') ?>" class="btn btn-outline-primary">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-user-plus"></use>
                        </svg>
                        Add New User
                    </a>
                    <a href="<?= base_url('/schedules/create') ?>" class="btn btn-outline-success">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-calendar-plus"></use>
                        </svg>
                        Create Schedule
                    </a>
                    <a href="<?= base_url('/assignments/create') ?>" class="btn btn-outline-info">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-document-plus"></use>
                        </svg>
                        New Assignment
                    </a>
                    <a href="<?= base_url('/reports') ?>" class="btn btn-outline-warning">
                        <svg class="pc-icon me-2">
                            <use xlink:href="#custom-chart-bar"></use>
                        </svg>
                        Generate Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and System Status -->
<div class="row">
    <!-- Recent Activity -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if (!empty($recent_activities)): ?>
                        <?php foreach ($recent_activities as $activity): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1"><?= $activity['title'] ?></h6>
                                    <p class="text-muted small mb-0"><?= $activity['description'] ?></p>
                                    <small class="text-muted"><?= $activity['time'] ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <svg class="pc-icon" style="font-size: 3rem;">
                                <use xlink:href="#custom-inbox"></use>
                            </svg>
                            <p class="mt-2">No recent activity</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5>System Status</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <svg class="pc-icon text-success" style="font-size: 1.5rem;">
                                    <use xlink:href="#custom-check-circle"></use>
                                </svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Database</h6>
                                <small class="text-muted">Online</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <svg class="pc-icon text-success" style="font-size: 1.5rem;">
                                    <use xlink:href="#custom-check-circle"></use>
                                </svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">API</h6>
                                <small class="text-muted">Healthy</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <svg class="pc-icon text-success" style="font-size: 1.5rem;">
                                    <use xlink:href="#custom-check-circle"></use>
                                </svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Storage</h6>
                                <small class="text-muted">85% Free</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <svg class="pc-icon text-warning" style="font-size: 1.5rem;">
                                    <use xlink:href="#custom-exclamation-triangle"></use>
                                </svg>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Backup</h6>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Recent Users</h5>
                <a href="<?= base_url('/users') ?>" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_users)): ?>
                                <?php foreach ($recent_users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="<?= base_url('assets/images/user/avatar-1.jpg') ?>" alt="user-image" class="user-avtar rounded-circle" style="width: 40px; height: 40px;">
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0"><?= $user['full_name'] ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $user['email'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'teacher' ? 'primary' : 'success') ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $user['status'] == 'active' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $user['last_login'] ?? 'Never' ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('/users/' . $user['id']) ?>" class="btn btn-outline-primary">
                                                    <svg class="pc-icon">
                                                        <use xlink:href="#custom-eye"></use>
                                                    </svg>
                                                </a>
                                                <a href="<?= base_url('/users/' . $user['id'] . '/edit') ?>" class="btn btn-outline-secondary">
                                                    <svg class="pc-icon">
                                                        <use xlink:href="#custom-edit"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <svg class="pc-icon" style="font-size: 3rem;">
                                            <use xlink:href="#custom-inbox"></use>
                                        </svg>
                                        <p class="mt-2">No users found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Initialize dashboard chart
    document.addEventListener('DOMContentLoaded', function() {
        // Chart will be initialized by the main app.js file
        console.log('Admin dashboard loaded');
    });
</script>
<?= $this->endSection() ?>