<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Create New User</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('users/create') ?>" method="POST" id="createUserForm">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= (isset($validation) && $validation->hasError('name')) ? 'is-invalid' : '' ?>"
                                id="name"
                                name="name"
                                value="<?= isset($name) ? $name : '' ?>"
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
                                value="<?= isset($email) ? $email : '' ?>"
                                placeholder="Enter email">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('email') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                    id="password"
                                    name="password"
                                    placeholder="Enter password">
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
                                <option value="" selected disabled>Select role</option>
                                <option value="Admin" <?= isset($role) && $role === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Doctor" <?= isset($role) && $role === 'Doctor' ? 'selected' : '' ?>>Doctor</option>
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
                                <i class="bi bi-person-plus"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card mt-4 shadow-sm d-none" id="previewCard">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Preview Data</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Name</th>
                                <td id="previewName"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="previewEmail"></td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td id="previewRole"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>