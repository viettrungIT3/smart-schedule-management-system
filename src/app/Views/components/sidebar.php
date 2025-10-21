<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="<?= base_url('dashboard') ?>" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="<?= base_url('assets/images/logo-dark.svg') ?>" alt="logo" class="img-fluid logo-lg">
                <span class="badge bg-light-success rounded-pill ms-2 theme-version">v1.0.0</span>
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="<?= base_url('assets/images/user/avatar-1.jpg') ?>" alt="user-image" class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0"><?= session()->get('user_name') ?? 'User' ?></h6>
                            <small><?= session()->get('user_role') ?? 'User' ?></small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="<?= base_url('profile') ?>">
                                <i class="ti ti-user"></i>
                                <span>My Account</span>
                            </a>
                            <a href="<?= base_url('settings') ?>">
                                <i class="ti ti-settings"></i>
                                <span>Settings</span>
                            </a>
                            <a href="#">
                                <i class="ti ti-lock"></i>
                                <span>Lock Screen</span>
                            </a>
                            <a href="<?= base_url('auth/logout') ?>">
                                <i class="ti ti-power"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>
                
                <!-- Dashboard -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('dashboard') ?>">Default</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('dashboard/analytics') ?>">Analytics</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('dashboard/finance') ?>">Finance</a></li>
                    </ul>
                </li>
                
                <!-- Schedule Management -->
                <li class="pc-item pc-caption">
                    <label>Schedule Management</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-calendar"></use>
                    </svg>
                </li>
                
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-calendar"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Schedules</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('schedules') ?>">View All</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('schedules/create') ?>">Create New</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('schedules/calendar') ?>">Calendar View</a></li>
                    </ul>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('rooms') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-building"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Rooms</span>
                    </a>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('subjects') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-book"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Subjects</span>
                    </a>
                </li>
                
                <!-- User Management -->
                <li class="pc-item pc-caption">
                    <label>User Management</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-users"></use>
                    </svg>
                </li>
                
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-users"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Users</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('users') ?>">All Users</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('users/teachers') ?>">Teachers</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('users/students') ?>">Students</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?= base_url('users/create') ?>">Add User</a></li>
                    </ul>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('roles') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-shield"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Roles & Permissions</span>
                    </a>
                </li>
                
                <!-- Reports -->
                <li class="pc-item pc-caption">
                    <label>Reports</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-chart-bar"></use>
                    </svg>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('reports') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-chart-bar"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Reports</span>
                    </a>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('attendance') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-clipboard-check"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Attendance</span>
                    </a>
                </li>
                
                <!-- Settings -->
                <li class="pc-item pc-caption">
                    <label>Settings</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-settings"></use>
                    </svg>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('settings') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-settings"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">System Settings</span>
                    </a>
                </li>
                
                <li class="pc-item">
                    <a href="<?= base_url('notifications') ?>" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-bell"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Notifications</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
