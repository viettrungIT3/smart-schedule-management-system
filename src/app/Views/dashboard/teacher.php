<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Teacher Dashboard - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-person-workspace me-2"></i>
                Teacher Dashboard
            </h1>
            <div class="text-muted">
                <i class="bi bi-calendar3 me-1"></i>
                <?= date('l, F j, Y') ?>
            </div>
        </div>
    </div>
</div>

<!-- Teacher Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-value"><?= $stats['my_classes'] ?? 0 ?></div>
                        <div class="stat-label">My Classes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-value"><?= $stats['today_schedules'] ?? 0 ?></div>
                        <div class="stat-label">Today's Classes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-value"><?= $stats['total_students'] ?? 0 ?></div>
                        <div class="stat-label">Total Students</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-value"><?= $stats['attendance_rate'] ?? 0 ?>%</div>
                        <div class="stat-label">Avg Attendance</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule and Quick Actions -->
<div class="row">
    <!-- Today's Schedule -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-day me-2"></i>
                    Today's Schedule
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($today_schedules)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($today_schedules as $schedule): ?>
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="schedule-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="schedule-time">
                                                <?= date('H:i', strtotime($schedule['start_time'])) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="schedule-subject">
                                                <?= $schedule['subject_name'] ?>
                                            </div>
                                            <div class="schedule-teacher">
                                                Class: <?= $schedule['class_name'] ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge bg-primary">
                                                <?= $schedule['room'] ?? 'TBD' ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('/attendance/mark/' . $schedule['id']) ?>" 
                                                   class="btn btn-outline-success">
                                                    <i class="bi bi-clipboard-check me-1"></i>
                                                    Mark Attendance
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x fs-1"></i>
                        <h5 class="mt-3">No classes today</h5>
                        <p>Enjoy your free time!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/schedules/create') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Create Schedule
                    </a>
                    <a href="<?= base_url('/attendance') ?>" class="btn btn-outline-success">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Mark Attendance
                    </a>
                    <a href="<?= base_url('/assignments/create') ?>" class="btn btn-outline-info">
                        <i class="bi bi-journal-plus me-2"></i>
                        New Assignment
                    </a>
                    <a href="<?= base_url('/students') ?>" class="btn btn-outline-warning">
                        <i class="bi bi-people me-2"></i>
                        View Students
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Classes -->
        <div class="card dashboard-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    Upcoming Classes
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($upcoming_classes)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($upcoming_classes as $class): ?>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0"><?= $class['subject_name'] ?></h6>
                                        <small class="text-muted"><?= $class['class_name'] ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-primary fw-bold">
                                            <?= date('H:i', strtotime($class['start_time'])) ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M j', strtotime($class['date'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-calendar-x"></i>
                        <p class="mt-2 mb-0">No upcoming classes</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Attendance and Notifications -->
<div class="row">
    <!-- Recent Attendance -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Recent Attendance
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_attendance)): ?>
                                <?php foreach ($recent_attendance as $attendance): ?>
                                    <tr>
                                        <td><?= $attendance['class_name'] ?></td>
                                        <td><?= date('M j', strtotime($attendance['date'])) ?></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <?= $attendance['present_count'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?= $attendance['absent_count'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" 
                                                     style="width: <?= $attendance['attendance_rate'] ?>%">
                                                    <?= $attendance['attendance_rate'] ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mt-2 mb-0">No attendance records</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notifications -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>
                    Notifications
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-<?= $notification['icon'] ?> text-<?= $notification['type'] ?>"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?= $notification['title'] ?></h6>
                                        <p class="text-muted small mb-0"><?= $notification['message'] ?></p>
                                        <small class="text-muted"><?= $notification['time'] ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-bell-slash fs-1"></i>
                            <p class="mt-2">No notifications</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Teacher dashboard loaded');
});
</script>
<?= $this->endSection() ?>
