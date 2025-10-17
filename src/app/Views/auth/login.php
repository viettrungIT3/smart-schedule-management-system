<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Login - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0">
            <div class="card-header text-center py-4">
                <h3 class="mb-0">
                    <i class="bi bi-calendar3 me-2"></i>
                    ScheduleFlow
                </h3>
                <p class="text-white-50 mb-0">Sign in to your account</p>
            </div>
            <div class="card-body p-4">
                <?= form_open('/login', ['class' => 'needs-validation', 'data-validate' => 'true', 'novalidate' => true]) ?>
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
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-1"></i>
                        Password
                    </label>
                    <div class="input-group">
                        <input type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required>
                        <button class="btn btn-outline-secondary"
                            type="button"
                            id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        Please provide a valid password.
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="auth_type" class="form-label">Authentication Type</label>
                    <select class="form-select" id="auth_type" name="auth_type">
                        <option value="jwt" <?= old('auth_type') == 'jwt' ? 'selected' : '' ?>>JWT (Recommended)</option>
                        <option value="session" <?= old('auth_type') == 'session' ? 'selected' : '' ?>>Session</option>
                        <option value="both" <?= old('auth_type') == 'both' ? 'selected' : '' ?>>Both</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>
                </div>
                <?= form_close() ?>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Don't have an account?
                        <a href="<?= base_url('/register') ?>" class="text-decoration-none">
                            Sign up here
                        </a>
                    </p>
                    <p class="text-muted mt-2">
                        <a href="<?= base_url('/forgot-password') ?>" class="text-decoration-none">
                            Forgot your password?
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Demo Accounts -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Demo Accounts
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        <button class="btn btn-outline-primary btn-sm w-100 demo-login"
                            data-email="admin@example.com"
                            data-password="admin123">
                            <i class="bi bi-person-badge me-1"></i>
                            Admin Account
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-success btn-sm w-100 demo-login"
                            data-email="teacher.a@example.com"
                            data-password="admin123">
                            <i class="bi bi-person-workspace me-1"></i>
                            Teacher
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info btn-sm w-100 demo-login"
                            data-email="student1@example.com"
                            data-password="admin123">
                            <i class="bi bi-person me-1"></i>
                            Student
                        </button>
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

        // Demo login buttons
        document.querySelectorAll('.demo-login').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.getAttribute('data-email');
                const password = this.getAttribute('data-password');

                document.getElementById('email').value = email;
                document.getElementById('password').value = password;

                // Highlight the filled fields
                document.getElementById('email').classList.add('border-success');
                document.getElementById('password').classList.add('border-success');

                setTimeout(() => {
                    document.getElementById('email').classList.remove('border-success');
                    document.getElementById('password').classList.remove('border-success');
                }, 2000);
            });
        });
    });
</script>
<?= $this->endSection() ?>