<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Patient Medical Records</h2>
                <a href="<?= base_url('medical-records/create/' . $patient['record_number']) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Visit
                </a>
            </div>

            <!-- Patient Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Record Number</label>
                            <p class="fw-bold mb-0"><?= esc($patient['record_number']) ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Name</label>
                            <p class="fw-bold mb-0"><?= esc($patient['name']) ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Date of Birth</label>
                            <p class="fw-bold mb-0"><?= date('d M Y', strtotime($patient['birth'])) ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="fw-bold mb-0"><?= esc($patient['phone']) ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">NIK</label>
                            <p class="fw-bold mb-0"><?= esc($patient['nik']) ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Blood Type</label>
                            <p class="fw-bold mb-0">
                                <span class="badge bg-<?= $patient['blood_type'] === 'O' ? 'success' : ($patient['blood_type'] === 'AB' ? 'info' : ($patient['blood_type'] === 'B' ? 'warning' : 'danger')) ?>">
                                    <?= esc($patient['blood_type']) ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Weight</label>
                            <p class="fw-bold mb-0"><?= esc($patient['weight']) ?> kg</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted">Height</label>
                            <p class="fw-bold mb-0"><?= esc($patient['height']) ?> cm</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Address</label>
                            <p class="fw-bold mb-0"><?= esc($patient['address']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Records Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Visit History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Symptoms</th>
                                    <th>Diagnosis</th>
                                    <th>ICD-10</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($records)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-3">No medical records found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($records as $record): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($record['date_visit'])) ?></td>
                                            <td><?= esc(substr($record['symptoms'], 0, 50)) . (strlen($record['symptoms']) > 50 ? '...' : '') ?></td>
                                            <td><?= esc(substr($record['doctor_diagnose'], 0, 50)) . (strlen($record['doctor_diagnose']) > 50 ? '...' : '') ?></td>
                                            <td>
                                                <span class="text-muted"><?= esc($record['icd10_code']) ?></span>
                                                <small class="d-block"><?= esc($record['icd10_name']) ?></small>
                                            </td>
                                            <td><?= esc($record['consultation_by']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $record['is_done'] ? 'success' : 'warning' ?>">
                                                    <?= $record['is_done'] ? 'Completed' : 'Pending' ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="<?= base_url('medical-records/detail/' . $record['id']) ?>"
                                                        class="btn btn-sm btn-info"
                                                        data-bs-toggle="tooltip"
                                                        title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if (!$record['is_done']): ?>
                                                        <a href="<?= base_url('medical-records/edit/' . $record['id']) ?>"
                                                            class="btn btn-sm btn-primary"
                                                            data-bs-toggle="tooltip"
                                                            title="Edit Record">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?= base_url('medical-records') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
<?= $this->endSection() ?>