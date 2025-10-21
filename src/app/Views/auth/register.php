<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Register - ScheduleFlow<?= $this->endSection() ?>

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
                        <h4 class="text-center f-w-500 mb-3 mt-3">Create Account</h4>
                        <p class="text-muted">Sign up to get started with ScheduleFlow</p>
                    </div>
                    
                    <?= form_open('auth/processRegister', ['class' => 'mt-4']) ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" value="<?= old('first_name') ?>" required>
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('first_name')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= session()->getFlashdata('validation')->getError('first_name') ?>
                                        </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" value="<?= old('last_name') ?>" required>
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('last_name')): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= session()->getFlashdata('validation')->getError('last_name') ?>
                                        </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
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
                            <small class="text-muted">Password must be at least 6 characters long</small>
                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('password')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= session()->getFlashdata('validation')->getError('password') ?>
                                    </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <svg class="pc-icon">
                                        <use xlink:href="#custom-eye"></use>
                                    </svg>
                                </button>
                            </div>
                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('password_confirm')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= session()->getFlashdata('validation')->getError('password_confirm') ?>
                                    </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Account Type</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select account type</option>
                                <option value="teacher" <?= old('role') == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                <option value="student" <?= old('role') == 'student' ? 'selected' : '' ?>>Student</option>
                            </select>
                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('role')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= session()->getFlashdata('validation')->getError('role') ?>
                                    </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input class="form-check-input input-primary" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label text-muted" for="terms">
                                I agree to the <a href="#" class="link-primary">Terms and Conditions</a>
                            </label>
                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('terms')): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= session()->getFlashdata('validation')->getError('terms') ?>
                                    </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                    <?= form_close() ?>
                    
                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <h6 class="f-w-500 mb-0">Already have an Account?</h6>
                        <a href="<?= base_url('auth/login') ?>" class="link-primary">Sign In</a>
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
    
    // Password confirm toggle
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirm');
    
    if (togglePasswordConfirm && passwordConfirmInput) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            
            const icon = this.querySelector('svg use');
            icon.setAttribute('xlink:href', type === 'password' ? '#custom-eye' : '#custom-eye-slash');
        });
    }
    
    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    
    function validatePasswordMatch() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Passwords do not match');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswordMatch);
    passwordConfirm.addEventListener('input', validatePasswordMatch);
});
</script>
<?= $this->endSection() ?>