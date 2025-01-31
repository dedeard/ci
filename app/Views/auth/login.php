<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>

<?= helper('form'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Login</h5>
                    </div>
                </div>

                <!-- Success Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <form action="<?= base_url('login') ?>" method="POST">
                        <?= csrf_field() ?>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>"
                                id="email"
                                name="email"
                                value="admin@simawi.com"
                                placeholder="Enter your email">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('email') : '' ?>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                id="password"
                                name="password"
                                value="admin123"
                                placeholder="Enter your password">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('password') : '' ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>

                    <!-- Accounts Note -->
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-3"><strong>Accounts</strong></h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Email:</strong> admin@simawi.com<br>
                                    <strong>Name:</strong> System Administrator<br>
                                    <strong>Password:</strong> admin123<br>
                                    <strong>Role:</strong> Admin
                                </li>
                                <hr class="my-2">
                                <li>
                                    <strong>Email:</strong> doctor@simawi.com<br>
                                    <strong>Name:</strong> Dr. John Doe<br>
                                    <strong>Password:</strong> doctor123<br>
                                    <strong>Role:</strong> Doctor
                                </li>
                                <hr class="my-2">
                                <li>
                                    <strong>Email:</strong> jane@simawi.com<br>
                                    <strong>Name:</strong> Dr. Jane Smith<br>
                                    <strong>Password:</strong> doctor123<br>
                                    <strong>Role:</strong> Doctor
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>