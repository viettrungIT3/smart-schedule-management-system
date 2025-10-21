<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Login - ScheduleFlow<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- [ Main Content ] start -->
<div class="auth-main">
    <div class="auth-wrapper v1">
        <div class="auth-form">
            <div class="card my-5">
                <div class="card-body">
                    <div class="text-center">
                        <a href="<?= base_url('/') ?>">
                            <img src="<?= base_url('assets/images/logo-dark.svg') ?>" alt="ScheduleFlow Logo">
                        </a>
                        <h4 class="text-center f-w-500 mb-3 mt-3">Welcome Back!</h4>
                        <p class="text-muted">Sign in to your account to continue</p>
                    </div>
                    
                    <?= form_open('auth/processLogin', ['class' => 'mt-4']) ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?= old('email') ?>" required>
                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('email')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= session()->getFlashdata('validation')->getError('email') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-eye"></use>
                                    </svg>
                                </button>
                            </div>
                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('password')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= session()->getFlashdata('validation')->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex mt-1 justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">Remember me?</label>
                            </div>
                            <a href="<?= base_url('auth/forgot-password') ?>" class="link-primary">Forgot Password?</a>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    <?= form_close() ?>
                    
                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <h6 class="f-w-500 mb-0">Don't have an Account?</h6>
                        <a href="<?= base_url('auth/register') ?>" class="link-primary">Create Account</a>
                    </div>
                    
                    <!-- Demo Accounts -->
                    <div class="mt-4">
                        <div class="saprator my-3">
                            <span>Demo Accounts</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm demo-login" data-email="admin@example.com" data-password="admin123">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-shield"></use>
                                </svg>
                                Admin Account
                            </button>
                            <button class="btn btn-outline-success btn-sm demo-login" data-email="teacher.a@example.com" data-password="admin123">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-user"></use>
                                </svg>
                                Teacher Account
                            </button>
                            <button class="btn btn-outline-info btn-sm demo-login" data-email="student1@example.com" data-password="admin123">
                                <svg class="pc-icon me-2">
                                    <use xlink:href="#custom-graduation-cap"></use>
                                </svg>
                                Student Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('svg use');
            icon.setAttribute('xlink:href', type === 'password' ? '#custom-eye' : '#custom-eye-slash');
        });
    }
    
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