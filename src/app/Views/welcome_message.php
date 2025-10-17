<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>ScheduleFlow - Smart Schedule Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="bi bi-calendar3 me-3"></i>
                    ScheduleFlow
                </h1>
                <p class="lead mb-4">
                    The smart schedule management system for educational institutions. 
                    Streamline your scheduling process with our comprehensive solution.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= base_url('/login') ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Get Started
                    </a>
                    <a href="<?= base_url('/register') ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-person-plus me-2"></i>
                        Sign Up
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-calendar3 display-1 opacity-75"></i>
            </div>
        </div>
    </div>
    </div>

<!-- Features Section -->
<div class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold mb-3">Why Choose ScheduleFlow?</h2>
                <p class="lead text-muted">Powerful features designed for modern educational institutions</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <h5 class="card-title">User Management</h5>
                        <p class="card-text text-muted">
                            Comprehensive user management with role-based access control for administrators, teachers, and students.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-week fs-3"></i>
                        </div>
                        <h5 class="card-title">Smart Scheduling</h5>
                        <p class="card-text text-muted">
                            Intelligent schedule management with conflict detection and automated optimization.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-clipboard-check fs-3"></i>
                        </div>
                        <h5 class="card-title">Attendance Tracking</h5>
                        <p class="card-text text-muted">
                            Real-time attendance tracking with detailed reports and analytics.
                        </p>
                    </div>
                </div>
    </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-bell fs-3"></i>
                        </div>
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text text-muted">
                            Automated notifications and reminders to keep everyone informed.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-graph-up fs-3"></i>
                        </div>
                        <h5 class="card-title">Analytics & Reports</h5>
                        <p class="card-text text-muted">
                            Comprehensive analytics and reporting tools for data-driven decisions.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <h5 class="card-title">Secure & Reliable</h5>
                        <p class="card-text text-muted">
                            Enterprise-grade security with JWT authentication and role-based permissions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-3">Ready to Get Started?</h2>
                <p class="lead text-muted mb-4">
                    Join thousands of educational institutions already using ScheduleFlow to manage their schedules efficiently.
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?= base_url('/register') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Account
                    </a>
                    <a href="<?= base_url('/docs') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-book me-2"></i>
                        View Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>

<!-- Footer Info -->
<div class="py-4 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">
                    <i class="bi bi-calendar3 me-2"></i>
                    ScheduleFlow
                </h5>
                <small class="text-muted">Smart Schedule Management System</small>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    &copy; <?= date('Y') ?> ScheduleFlow. All rights reserved.
                </small>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
.hero-section {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
}

.min-vh-50 {
    min-height: 50vh;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
<?= $this->endSection() ?>