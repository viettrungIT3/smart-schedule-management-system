<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'ScheduleFlow - Smart Schedule Management' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon">
    
    <!-- Google Font -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/fonts/inter/inter.css') ?>" id="main-font-link">
    
    <!-- Phosphor Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/fonts/phosphor/duotone/style.css') ?>">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/fonts/tabler-icons.min.css') ?>">
    
    <!-- Feather Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/fonts/feather.css') ?>">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/fonts/fontawesome.css') ?>">
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/fonts/material.css') ?>">
    
    <!-- Able Pro Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/css/style.css') ?>" id="main-style-link">
    <link rel="stylesheet" href="<?= base_url('assets/ablepro/css/style-preset.css') ?>">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    
    <!-- SVG Icons Sprite -->
    <?= file_get_contents(FCPATH . 'assets/images/icons.svg') ?>
    
    <!-- Additional CSS -->
    <?= $this->renderSection('css') ?>
</head>
<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="light">
    <!-- Pre-loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    
    <!-- App Shell -->
    <?= $this->include('components/header') ?>
    <?= $this->include('components/sidebar') ?>
    
    <!-- Main Content -->
    <main class="pc-container">
        <div class="pc-content">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="ti ti-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('warning') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="ti ti-info-circle me-2"></i>
                        <?= session()->getFlashdata('info') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    
    <!-- Theme Customizer Button -->
    <div class="pct-c-btn">
        <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
            <i class="ph-duotone ph-gear-six"></i>
        </a>
    </div>
    
    <!-- Theme Customizer Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout" aria-labelledby="offcanvas_pc_layout_label">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvas_pc_layout_label">Theme Settings</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-3">
                <label class="form-label d-block">Theme</label>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="document.body.setAttribute('data-pc-theme','light')">Light</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="document.body.setAttribute('data-pc-theme','dark')">Dark</button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Sidebar Caption</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="toggleSidebarCaption" checked onchange="document.body.setAttribute('data-pc-sidebar-caption', this.checked ? 'true' : 'false')">
                    <label class="form-check-label" for="toggleSidebarCaption">Show captions</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Preset</label>
                <div class="d-flex gap-2 flex-wrap">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.body.setAttribute('data-pc-preset','preset-<?= $i ?>')">Preset <?= $i ?></button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?= $this->include('components/footer') ?>
    
    <!-- Able Pro Core JS -->
    <script src="<?= base_url('assets/ablepro/js/plugins/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/ablepro/js/plugins/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/ablepro/js/plugins/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/ablepro/js/pcoded.js') ?>"></script>
    
    <!-- Chart.js for dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    
    <!-- Additional JS -->
    <?= $this->renderSection('js') ?>
</body>
</html>