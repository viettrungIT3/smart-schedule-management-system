<!-- [ Header ] start -->
<header class="pc-header">
    <div class="header-wrapper">
        <!-- Mobile menu toggle -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-menu-left"></use>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="#" class="dropdown-item">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-user"></use>
                            </svg>
                            <span>Profile</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-settings"></use>
                            </svg>
                            <span>Settings</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('auth/logout') ?>" class="dropdown-item">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-logout"></use>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- Search -->
        <div class="ms-auto">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">
                    <svg class="pc-icon">
                        <use xlink:href="#custom-search"></use>
                    </svg>
                </span>
                <input type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon1">
            </div>
        </div>
        
        <!-- User menu -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <!-- Notifications -->
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-notification"></use>
                        </svg>
                        <span class="badge bg-danger pc-h-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <div class="px-3 py-2 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Notifications</h6>
                            <span class="badge bg-light-success text-success">3 new</span>
                        </div>
                        <div class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <svg class="pc-icon text-primary">
                                        <use xlink:href="#custom-calendar"></use>
                                    </svg>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">New schedule created</h6>
                                    <small class="text-muted">2 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <svg class="pc-icon text-warning">
                                        <use xlink:href="#custom-user-plus"></use>
                                    </svg>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">New user registered</h6>
                                    <small class="text-muted">5 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <svg class="pc-icon text-info">
                                        <use xlink:href="#custom-mail"></use>
                                    </svg>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">New message</h6>
                                    <small class="text-muted">10 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 py-2">
                            <a href="#" class="btn btn-primary btn-sm w-100">View all notifications</a>
                        </div>
                    </div>
                </li>
                
                <!-- User profile -->
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?= base_url('assets/images/user/avatar-1.jpg') ?>" alt="user-image" class="user-avtar rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <div class="px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="<?= base_url('assets/images/user/avatar-1.jpg') ?>" alt="user-image" class="user-avtar rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0"><?= session()->get('user_name') ?? 'User' ?></h6>
                                    <small class="text-muted"><?= session()->get('user_role') ?? 'User' ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 py-2">
                            <a href="<?= base_url('profile') ?>" class="dropdown-item">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-user"></use>
                                </svg>
                                <span>My Account</span>
                            </a>
                            <a href="<?= base_url('settings') ?>" class="dropdown-item">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-settings"></use>
                                </svg>
                                <span>Settings</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-lock"></use>
                                </svg>
                                <span>Lock Screen</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= base_url('auth/logout') ?>" class="dropdown-item">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-logout"></use>
                                </svg>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- [ Header ] end -->
