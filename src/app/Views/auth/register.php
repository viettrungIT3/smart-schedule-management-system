<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Register - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-header text-center py-4">
                <h3 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>
                    Create Account
                </h3>
                <p class="text-white-50 mb-0">Join ScheduleFlow today</p>
            </div>
            <div class="card-body p-4">
                <?= form_open('/register', ['class' => 'needs-validation', 'data-validate' => 'true', 'novalidate' => true]) ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">
                            <i class="bi bi-person me-1"></i>
                            First Name
                        </label>
                        <input type="text"
                            class="form-control"
                            id="first_name"
                            name="first_name"
                            value="<?= old('first_name') ?>"
                            placeholder="Enter first name"
                            required>
                        <div class="invalid-feedback">
                            Please provide your first name.
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">
                            <i class="bi bi-person me-1"></i>
                            Last Name
                        </label>
                        <input type="text"
                            class="form-control"
                            id="last_name"
                            name="last_name"
                            value="<?= old('last_name') ?>"
                            placeholder="Enter last name"
                            required>
                        <div class="invalid-feedback">
                            Please provide your last name.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-1"></i>
                        Email Address
                    </label>
                    <input type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?= old('email') ?>"
                        placeholder="Enter your email"
                        required>
                    <div class="invalid-feedback">
                        Please provide a valid email address.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">
                        <i class="bi bi-person-badge me-1"></i>
                        Account Type
                    </label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select account type</option>
                        <option value="student" <?= old('role') == 'student' ? 'selected' : '' ?>>
                            Student
                        </option>
                        <option value="teacher" <?= old('role') == 'teacher' ? 'selected' : '' ?>>
                            Teacher
                        </option>
                    </select>
                    <div class="invalid-feedback">
                        Please select an account type.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-1"></i>
                        Password
                    </label>
                    <div class="input-group">
                        <input type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Create a password"
                            required>
                        <button class="btn btn-outline-secondary"
                            type="button"
                            id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="form-text">
                        Password must be at least 8 characters with uppercase, lowercase, and number.
                    </div>
                    <div class="invalid-feedback">
                        Password must be at least 8 characters with uppercase, lowercase, and number.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i>
                        Confirm Password
                    </label>
                    <input type="password"
                        class="form-control"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Confirm your password"
                        required>
                    <div class="invalid-feedback">
                        Passwords do not match.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            id="terms"
                            name="terms"
                            required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="<?= base_url('/terms') ?>" target="_blank">Terms of Service</a>
                            and <a href="<?= base_url('/privacy') ?>" target="_blank">Privacy Policy</a>
                        </label>
                        <div class="invalid-feedback">
                            You must agree to the terms and conditions.
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Account
                    </button>
                </div>
                <?= form_close() ?>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Already have an account?
                        <a href="<?= base_url('/login') ?>" class="text-decoration-none">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Account Type Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Account Types
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person text-primary fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Student Account</h6>
                                <p class="text-muted small mb-0">
                                    View schedules, check attendance, and receive notifications.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-workspace text-success fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Teacher Account</h6>
                                <p class="text-muted small mb-0">
                                    Manage schedules, mark attendance, and create assignments.
                                </p>
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
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });

        // Password confirmation validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        function validatePasswordMatch() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        password.addEventListener('input', validatePasswordMatch);
        confirmPassword.addEventListener('input', validatePasswordMatch);

        // Form validation
        const form = document.querySelector('form[data-validate]');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>
<?= $this->endSection() ?>