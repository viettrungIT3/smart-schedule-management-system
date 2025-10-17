<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
            <i class="bi bi-calendar3 me-2"></i>
            ScheduleFlow
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (session()->get('logged_in')): ?>
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Schedules -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/schedules') ?>">
                            <i class="bi bi-calendar-week me-1"></i>
                            Schedules
                        </a>
                    </li>

                    <!-- Users (Admin/Teacher only) -->
                    <?php if (in_array(session()->get('user_role'), ['admin', 'teacher'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/users') ?>">
                                <i class="bi bi-people me-1"></i>
                                Users
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Attendance (Teacher/Student) -->
                    <?php if (in_array(session()->get('user_role'), ['teacher', 'student'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/attendance') ?>">
                                <i class="bi bi-clipboard-check me-1"></i>
                                Attendance
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Notifications -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/notifications') ?>">
                            <i class="bi bi-bell me-1"></i>
                            Notifications
                            <span class="badge bg-danger ms-1">3</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Right Side -->
            <ul class="navbar-nav">
                <?php if (session()->get('logged_in')): ?>
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            <?= session()->get('user_name') ?? 'User' ?>
                            <span class="badge bg-secondary ms-2"><?= ucfirst(session()->get('user_role')) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('/profile') ?>">
                                    <i class="bi bi-person me-2"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('/settings') ?>">
                                    <i class="bi bi-gear me-2"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Login/Register -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/register') ?>">
                            <i class="bi bi-person-plus me-1"></i>
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>