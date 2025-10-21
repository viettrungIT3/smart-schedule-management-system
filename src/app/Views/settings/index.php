<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>System Settings - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">System Settings</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Settings Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-settings"></use>
                            </svg>
                            General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-controls="appearance" aria-selected="false">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-palette"></use>
                            </svg>
                            Appearance
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-bell"></use>
                            </svg>
                            Notifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-shield"></use>
                            </svg>
                            Security
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button" role="tab" aria-controls="backup" aria-selected="false">
                            <svg class="pc-icon me-2">
                                <use xlink:href="#custom-database"></use>
                            </svg>
                            Backup
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="settingsTabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <form id="generalSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Application Settings</h6>
                                    <div class="mb-3">
                                        <label for="app_name" class="form-label">Application Name</label>
                                        <input type="text" class="form-control" id="app_name" name="app_name" value="ScheduleFlow" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="app_url" class="form-label">Application URL</label>
                                        <input type="url" class="form-control" id="app_url" name="app_url" value="<?= base_url() ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="timezone" class="form-label">Timezone</label>
                                        <select class="form-select" id="timezone" name="timezone" required>
                                            <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (GMT+7)</option>
                                            <option value="UTC">UTC (GMT+0)</option>
                                            <option value="America/New_York">America/New_York (GMT-5)</option>
                                            <option value="Europe/London">Europe/London (GMT+0)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">System Information</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Version</label>
                                        <input type="text" class="form-control" value="1.0.0" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">PHP Version</label>
                                        <input type="text" class="form-control" value="<?= PHP_VERSION ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CodeIgniter Version</label>
                                        <input type="text" class="form-control" value="<?= \CodeIgniter\CodeIgniter::CI_VERSION ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="pc-icon me-2">
                                        <use xlink:href="#custom-save"></use>
                                    </svg>
                                    Save General Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Appearance Settings -->
                    <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                        <form id="appearanceSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Theme Settings</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Default Theme</label>
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="theme" id="theme-light" value="light" checked>
                                            <label class="btn btn-outline-secondary" for="theme-light">Light</label>
                                            
                                            <input type="radio" class="btn-check" name="theme" id="theme-dark" value="dark">
                                            <label class="btn btn-outline-secondary" for="theme-dark">Dark</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Color Preset</label>
                                        <select class="form-select" id="color_preset" name="color_preset">
                                            <option value="preset-1">Preset 1 (Default)</option>
                                            <option value="preset-2">Preset 2</option>
                                            <option value="preset-3">Preset 3</option>
                                            <option value="preset-4">Preset 4</option>
                                            <option value="preset-5">Preset 5</option>
                                            <option value="preset-6">Preset 6</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="sidebar_caption" name="sidebar_caption" checked>
                                            <label class="form-check-label" for="sidebar_caption">Show Sidebar Captions</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Layout Settings</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Sidebar Position</label>
                                        <select class="form-select" id="sidebar_position" name="sidebar_position">
                                            <option value="left">Left</option>
                                            <option value="right">Right</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Header Style</label>
                                        <select class="form-select" id="header_style" name="header_style">
                                            <option value="default">Default</option>
                                            <option value="fixed">Fixed</option>
                                            <option value="sticky">Sticky</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="compact_mode" name="compact_mode">
                                            <label class="form-check-label" for="compact_mode">Compact Mode</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="pc-icon me-2">
                                        <use xlink:href="#custom-save"></use>
                                    </svg>
                                    Save Appearance Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Notifications Settings -->
                    <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                        <form id="notificationsSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Email Notifications</h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="email_schedule_reminder" name="email_schedule_reminder" checked>
                                            <label class="form-check-label" for="email_schedule_reminder">Schedule Reminders</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="email_attendance" name="email_attendance" checked>
                                            <label class="form-check-label" for="email_attendance">Attendance Reports</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="email_system" name="email_system" checked>
                                            <label class="form-check-label" for="email_system">System Notifications</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Push Notifications</h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="push_schedule" name="push_schedule" checked>
                                            <label class="form-check-label" for="push_schedule">Schedule Updates</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="push_attendance" name="push_attendance">
                                            <label class="form-check-label" for="push_attendance">Attendance Alerts</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="push_system" name="push_system" checked>
                                            <label class="form-check-label" for="push_system">System Alerts</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="pc-icon me-2">
                                        <use xlink:href="#custom-save"></use>
                                    </svg>
                                    Save Notification Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <form id="securitySettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Authentication</h6>
                                    <div class="mb-3">
                                        <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                        <input type="number" class="form-control" id="session_timeout" name="session_timeout" value="120" min="5" max="1440">
                                    </div>
                                    <div class="mb-3">
                                        <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                                        <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" value="5" min="3" max="10">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="two_factor" name="two_factor">
                                            <label class="form-check-label" for="two_factor">Enable Two-Factor Authentication</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Password Policy</h6>
                                    <div class="mb-3">
                                        <label for="min_password_length" class="form-label">Minimum Password Length</label>
                                        <input type="number" class="form-control" id="min_password_length" name="min_password_length" value="6" min="4" max="20">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_uppercase" name="require_uppercase">
                                            <label class="form-check-label" for="require_uppercase">Require Uppercase Letters</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_numbers" name="require_numbers">
                                            <label class="form-check-label" for="require_numbers">Require Numbers</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="require_symbols" name="require_symbols">
                                            <label class="form-check-label" for="require_symbols">Require Special Characters</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="pc-icon me-2">
                                        <use xlink:href="#custom-save"></use>
                                    </svg>
                                    Save Security Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Backup Settings -->
                    <div class="tab-pane fade" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Database Backup</h6>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup" checked>
                                        <label class="form-check-label" for="auto_backup">Enable Automatic Backup</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="backup_frequency" class="form-label">Backup Frequency</label>
                                    <select class="form-select" id="backup_frequency" name="backup_frequency">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="backup_retention" class="form-label">Retention Period (days)</label>
                                    <input type="number" class="form-control" id="backup_retention" name="backup_retention" value="30" min="7" max="365">
                                </div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success" onclick="createBackup()">
                                        <svg class="pc-icon me-2">
                                            <use xlink:href="#custom-database"></use>
                                        </svg>
                                        Create Backup Now
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Recent Backups</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Size</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2024-01-15 10:30</td>
                                                <td>2.5 MB</td>
                                                <td><span class="badge bg-success">Success</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">Download</button>
                                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2024-01-14 10:30</td>
                                                <td>2.4 MB</td>
                                                <td><span class="badge bg-success">Success</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">Download</button>
                                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    // Load current settings
    loadSettings();
    
    // Form submission handlers
    document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('general', new FormData(this));
    });
    
    document.getElementById('appearanceSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('appearance', new FormData(this));
    });
    
    document.getElementById('notificationsSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('notifications', new FormData(this));
    });
    
    document.getElementById('securitySettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('security', new FormData(this));
    });
});

function loadSettings() {
    // Load settings from API
    fetch('<?= base_url('api/settings') ?>', {
        headers: {
            'Authorization': 'Bearer ' + getToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const settings = data.data;
            
            // Populate general settings
            if (settings.general) {
                document.getElementById('app_name').value = settings.general.app_name || 'ScheduleFlow';
                document.getElementById('app_url').value = settings.general.app_url || '<?= base_url() ?>';
                document.getElementById('timezone').value = settings.general.timezone || 'Asia/Ho_Chi_Minh';
            }
            
            // Populate appearance settings
            if (settings.appearance) {
                document.querySelector(`input[name="theme"][value="${settings.appearance.theme || 'light'}"]`).checked = true;
                document.getElementById('color_preset').value = settings.appearance.color_preset || 'preset-1';
                document.getElementById('sidebar_caption').checked = settings.appearance.sidebar_caption || false;
                document.getElementById('sidebar_position').value = settings.appearance.sidebar_position || 'left';
                document.getElementById('header_style').value = settings.appearance.header_style || 'default';
                document.getElementById('compact_mode').checked = settings.appearance.compact_mode || false;
            }
            
            // Populate notification settings
            if (settings.notifications) {
                document.getElementById('email_schedule_reminder').checked = settings.notifications.email_schedule_reminder || false;
                document.getElementById('email_attendance').checked = settings.notifications.email_attendance || false;
                document.getElementById('email_system').checked = settings.notifications.email_system || false;
                document.getElementById('push_schedule').checked = settings.notifications.push_schedule || false;
                document.getElementById('push_attendance').checked = settings.notifications.push_attendance || false;
                document.getElementById('push_system').checked = settings.notifications.push_system || false;
            }
            
            // Populate security settings
            if (settings.security) {
                document.getElementById('session_timeout').value = settings.security.session_timeout || 120;
                document.getElementById('max_login_attempts').value = settings.security.max_login_attempts || 5;
                document.getElementById('two_factor').checked = settings.security.two_factor || false;
                document.getElementById('min_password_length').value = settings.security.min_password_length || 6;
                document.getElementById('require_uppercase').checked = settings.security.require_uppercase || false;
                document.getElementById('require_numbers').checked = settings.security.require_numbers || false;
                document.getElementById('require_symbols').checked = settings.security.require_symbols || false;
            }
        }
    })
    .catch(error => console.error('Error loading settings:', error));
}

function saveSettings(category, formData) {
    const data = Object.fromEntries(formData);
    
    fetch(`<?= base_url('api/settings/') ?>${category}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + getToken()
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${category.charAt(0).toUpperCase() + category.slice(1)} settings saved successfully`, 'success');
            
            // Apply appearance changes immediately
            if (category === 'appearance') {
                applyAppearanceSettings(data.data);
            }
        } else {
            showNotification(`Error saving ${category} settings: ${data.message}`, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(`Error saving ${category} settings`, 'error');
    });
}

function applyAppearanceSettings(settings) {
    // Apply theme changes
    if (settings.theme) {
        document.body.setAttribute('data-pc-theme', settings.theme);
    }
    
    // Apply preset changes
    if (settings.color_preset) {
        document.body.setAttribute('data-pc-preset', settings.color_preset);
    }
    
    // Apply sidebar caption
    if (settings.sidebar_caption !== undefined) {
        document.body.setAttribute('data-pc-sidebar-caption', settings.sidebar_caption ? 'true' : 'false');
    }
}

function createBackup() {
    if (confirm('Are you sure you want to create a backup now? This may take a few minutes.')) {
        fetch('<?= base_url('api/backup/create') ?>', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + getToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Backup created successfully', 'success');
                // Refresh backup list
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification('Error creating backup: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error creating backup', 'error');
        });
    }
}

function getToken() {
    return localStorage.getItem('access_token') || '';
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
