<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?= helper('form'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= !empty($patient) ? 'Update Patient' : 'Create New Patient' ?></h5>
                    </div>
                </div>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <form action="<?= !empty($patient) ? base_url('patients/edit/' . $patient['id']) : base_url('patients/create') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= (isset($validation) && $validation->hasError('name')) ? 'is-invalid' : '' ?>"
                                id="name"
                                name="name"
                                value="<?= $old['name'] ?? $patient['name'] ?? '' ?>"
                                placeholder="Enter patient name">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('name') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="birth" class="form-label">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control <?= (isset($validation) && $validation->hasError('birth')) ? 'is-invalid' : '' ?>"
                                id="birth"
                                name="birth"
                                value="<?= $old['birth'] ?? $patient['birth'] ?? '' ?>">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('birth') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nik" class="form-label">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= (isset($validation) && $validation->hasError('nik')) ? 'is-invalid' : '' ?>"
                                id="nik"
                                name="nik"
                                value="<?= $old['nik'] ?? $patient['nik'] ?? '' ?>"
                                placeholder="Enter NIK number">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('nik') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel"
                                class="form-control <?= (isset($validation) && $validation->hasError('phone')) ? 'is-invalid' : '' ?>"
                                id="phone"
                                name="phone"
                                value="<?= $old['phone'] ?? $patient['phone'] ?? '' ?>"
                                placeholder="Enter phone number">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('phone') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                Address <span class="text-danger">*</span>
                            </label>
                            <textarea
                                class="form-control <?= (isset($validation) && $validation->hasError('address')) ? 'is-invalid' : '' ?>"
                                id="address"
                                name="address"
                                rows="3"
                                placeholder="Enter complete address"><?= $old['address'] ?? $patient['address'] ?? '' ?></textarea>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('address') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="blood_type" class="form-label">
                                Blood Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= validation_show_error('blood_type') ? 'is-invalid' : '' ?>"
                                id="blood_type"
                                name="blood_type">
                                <option value="">Select blood type</option>
                                <?php foreach (['A', 'B', 'AB', 'O'] as $type): ?>
                                    <option value="<?= $type ?>" <?= ($old['blood_type'] ?? $patient['blood_type'] ?? '') === $type ? 'selected' : '' ?>>
                                        <?= $type ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= validation_show_error('blood_type') ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="weight" class="form-label">
                                Weight (kg) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?= (isset($validation) && $validation->hasError('weight')) ? 'is-invalid' : '' ?>"
                                id="weight"
                                name="weight"
                                step="0.1"
                                value="<?= $old['weight'] ?? $patient['weight'] ?? '' ?>"
                                placeholder="Enter weight in kg">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('weight') : '' ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="height" class="form-label">
                                Height (cm) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?= (isset($validation) && $validation->hasError('height')) ? 'is-invalid' : '' ?>"
                                id="height"
                                name="height"
                                value="<?= $old['height'] ?? $patient['height'] ?? '' ?>"
                                placeholder="Enter height in cm">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('height') : '' ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('patients') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> <?= !empty($patient) ? 'Update Patient' : 'Create Patient' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>