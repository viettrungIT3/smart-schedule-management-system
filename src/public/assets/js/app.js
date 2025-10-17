/**
 * ScheduleFlow Frontend JavaScript
 * Main application JavaScript file
 */

// Global App Object
window.ScheduleFlow = {
    // Configuration
    config: {
        apiBaseUrl: '/api',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        userRole: document.querySelector('meta[name="user-role"]')?.getAttribute('content'),
        userId: document.querySelector('meta[name="user-id"]')?.getAttribute('content')
    },
    
    // Initialize app
    init: function() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAjaxDefaults();
    },
    
    // Setup global event listeners
    setupEventListeners: function() {
        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
        
        // Form validation
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', this.validateForm);
        });
        
        // Confirm dialogs
        document.querySelectorAll('[data-confirm]').forEach(element => {
            element.addEventListener('click', this.confirmAction);
        });
    },
    
    // Initialize components
    initializeComponents: function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
        
        // Initialize charts if present
        this.initializeCharts();
    },
    
    // Setup AJAX defaults
    setupAjaxDefaults: function() {
        // Set CSRF token for all AJAX requests
        if (this.config.csrfToken) {
            $.ajaxSetup({
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', ScheduleFlow.config.csrfToken);
                }
            });
        }
    },
    
    // Form validation
    validateForm: function(event) {
        const form = event.target;
        const formData = new FormData(form);
        let isValid = true;
        
        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.remove();
        });
        
        // Validate required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                ScheduleFlow.showFieldError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Validate email fields
        form.querySelectorAll('input[type="email"]').forEach(field => {
            if (field.value && !ScheduleFlow.isValidEmail(field.value)) {
                field.classList.add('is-invalid');
                ScheduleFlow.showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            }
        });
        
        // Validate password fields
        form.querySelectorAll('input[type="password"]').forEach(field => {
            if (field.value && field.value.length < 6) {
                field.classList.add('is-invalid');
                ScheduleFlow.showFieldError(field, 'Password must be at least 6 characters long');
                isValid = false;
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    },
    
    // Show field error
    showFieldError: function(field, message) {
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        field.parentNode.appendChild(feedback);
    },
    
    // Email validation
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    // Confirm action
    confirmAction: function(event) {
        const message = event.target.getAttribute('data-confirm') || 'Are you sure?';
        if (!confirm(message)) {
            event.preventDefault();
        }
    },
    
    // Initialize charts
    initializeCharts: function() {
        // Dashboard charts
        const dashboardChart = document.getElementById('dashboardChart');
        if (dashboardChart) {
            this.createDashboardChart(dashboardChart);
        }
        
        // Attendance chart
        const attendanceChart = document.getElementById('attendanceChart');
        if (attendanceChart) {
            this.createAttendanceChart(attendanceChart);
        }
    },
    
    // Create dashboard chart
    createDashboardChart: function(canvas) {
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: [
                        '#198754',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    },
    
    // Create attendance chart
    createAttendanceChart: function(canvas) {
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Attendance Rate',
                    data: [85, 92, 78, 96, 88, 75, 90],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    },
    
    // API Helper methods
    api: {
        // Make API request
        request: function(url, options = {}) {
            const defaultOptions = {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            };
            
            const config = { ...defaultOptions, ...options };
            
            return fetch(ScheduleFlow.config.apiBaseUrl + url, config)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                });
        },
        
        // GET request
        get: function(url) {
            return this.request(url);
        },
        
        // POST request
        post: function(url, data) {
            return this.request(url, {
                method: 'POST',
                body: JSON.stringify(data)
            });
        },
        
        // PUT request
        put: function(url, data) {
            return this.request(url, {
                method: 'PUT',
                body: JSON.stringify(data)
            });
        },
        
        // DELETE request
        delete: function(url) {
            return this.request(url, {
                method: 'DELETE'
            });
        }
    },
    
    // Utility methods
    utils: {
        // Format date
        formatDate: function(date, format = 'YYYY-MM-DD') {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            
            return format
                .replace('YYYY', year)
                .replace('MM', month)
                .replace('DD', day);
        },
        
        // Format time
        formatTime: function(time) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        },
        
        // Show loading spinner
        showLoading: function(element) {
            element.innerHTML = '<div class="loading-spinner"></div>';
        },
        
        // Hide loading spinner
        hideLoading: function(element, originalContent) {
            element.innerHTML = originalContent;
        },
        
        // Show notification
        showNotification: function(message, type = 'info') {
            const alertClass = `alert-${type}`;
            const iconClass = {
                'success': 'bi-check-circle',
                'error': 'bi-exclamation-triangle',
                'warning': 'bi-exclamation-triangle',
                'info': 'bi-info-circle'
            }[type] || 'bi-info-circle';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="bi ${iconClass} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const container = document.querySelector('.container-fluid');
            if (container) {
                container.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    const alert = container.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }
        }
    }
};

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    ScheduleFlow.init();
});

// Export for global access
window.ScheduleFlow = ScheduleFlow;
