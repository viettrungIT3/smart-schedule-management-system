<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Student Dashboard - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-person me-2"></i>
                Student Dashboard
            </h1>
            <div class="text-muted">
                <i class="bi bi-calendar3 me-1"></i>
                <?= date('l, F j, Y') ?>
            </div>
        </div>
    </div>
</div>

<!-- Student Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-value"><?= $stats['attendance_rate'] ?? 0 ?>%</div>
                        <div class="stat-label">Attendance Rate</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clipboard-check"></i>
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
                        <div class="stat-value"><?= $stats['classes_attended'] ?? 0 ?></div>
                        <div class="stat-label">Classes Attended</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
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
                        <div class="stat-value"><?= $stats['total_subjects'] ?? 0 ?></div>
                        <div class="stat-label">Subjects</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-book"></i>
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
                        <div class="stat-value"><?= $stats['upcoming_classes'] ?? 0 ?></div>
                        <div class="stat-label">Today's Classes</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule and Attendance Chart -->
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
                                                Teacher: <?= $schedule['teacher_name'] ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge bg-primary">
                                                <?= $schedule['room'] ?? 'TBD' ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <?php if ($schedule['attendance_status']): ?>
                                                <span class="badge bg-<?= $schedule['attendance_status'] == 'present' ? 'success' : 'danger' ?>">
                                                    <?= ucfirst($schedule['attendance_status']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    Not Marked
                                                </span>
                                            <?php endif; ?>
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
    
    <!-- Attendance Chart -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Attendance Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="200"></canvas>
            </div>
        </div>
        
        <!-- Quick Info -->
        <div class="card dashboard-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Quick Info
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                            <h6 class="mt-2 mb-0"><?= $stats['present_days'] ?? 0 ?></h6>
                            <small class="text-muted">Present Days</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <i class="bi bi-x-circle text-danger fs-4"></i>
                            <h6 class="mt-2 mb-0"><?= $stats['absent_days'] ?? 0 ?></h6>
                            <small class="text-muted">Absent Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Schedule and Notifications -->
<div class="row">
    <!-- Weekly Schedule -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    This Week's Schedule
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($weekly_schedule)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Room</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekly_schedule as $day => $classes): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <tr class="<?= $class['is_today'] ? 'table-primary' : '' ?>">
                                            <td>
                                                <strong><?= $day ?></strong>
                                                <?php if ($class['is_today']): ?>
                                                    <span class="badge bg-primary ms-1">Today</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('H:i', strtotime($class['start_time'])) ?></td>
                                            <td><?= $class['subject_name'] ?></td>
                                            <td><?= $class['teacher_name'] ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= $class['room'] ?? 'TBD' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x"></i>
                        <p class="mt-2">No schedule available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Notifications and Announcements -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>
                    Notifications & Announcements
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
        
        <!-- Study Tips -->
        <div class="card dashboard-card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Study Tips
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 px-0 py-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Review your schedule daily</small>
                    </div>
                    <div class="list-group-item border-0 px-0 py-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Arrive 5 minutes early</small>
                    </div>
                    <div class="list-group-item border-0 px-0 py-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small>Check notifications regularly</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Student dashboard loaded');
});
</script>
<?= $this->endSection() ?>
