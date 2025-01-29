<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= !empty($user) ? 'Update User' : 'Create New User' ?></h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= !empty($user) ? base_url('users/edit/' . $user['id']) : base_url('users/create') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= (isset($validation) && $validation->hasError('name')) ? 'is-invalid' : '' ?>"
                                id="name"
                                name="name"
                                value="<?= $old['name'] ?? $user['name'] ?? '' ?>"
                                placeholder="Enter name">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('name') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>"
                                id="email"
                                name="email"
                                value="<?= $old['email'] ?? $user['email'] ?? '' ?>"
                                placeholder="Enter email">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('email') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                                <?php if (!empty($user)): ?>
                                    <small class="text-muted">(Leave blank to keep current password)</small>
                                <?php else: ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                    id="password"
                                    name="password"
                                    placeholder="Enter <?= !empty($user) ? 'new ' : '' ?>password">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('password') : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= (isset($validation) && $validation->hasError('role')) ? 'is-invalid' : '' ?>"
                                id="role"
                                name="role">
                                <option value="" disabled>Select role</option>
                                <option value="Admin" <?= ($old['role'] ?? $user['role'] ?? '') === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Doctor" <?= ($old['role'] ?? $user['role'] ?? '') === 'Doctor' ? 'selected' : '' ?>>Doctor</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('role') : '' ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> <?= !empty($user) ? 'Update User' : 'Create User' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>