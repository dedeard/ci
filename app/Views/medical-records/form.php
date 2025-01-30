<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?= helper('form'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"> <?= !empty($visit) ? 'Edit Visit' : 'Create New Visit' ?> </h5>
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
                    <!-- Patient Info Summary -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Patient:</strong> <?= esc($patient['name']) ?></p>
                                <p class="mb-1"><strong>Record #:</strong> <?= esc($patient['record_number']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Birth:</strong> <?= date('d M Y', strtotime($patient['birth'])) ?></p>
                                <p class="mb-1"><strong>Blood Type:</strong> <?= esc($patient['blood_type']) ?></p>
                            </div>
                        </div>
                    </div>
                    <form action="<?= !empty($visit) ? base_url('medical-records/edit/' . $visit['id']) : base_url('medical-records/create/' . $patient['record_number']) ?>" method="POST">

                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="date_visit" class="form-label">
                                Visit Date <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                class="form-control <?= (isset($validation) && $validation->hasError('date_visit')) ? 'is-invalid' : '' ?>"
                                id="date_visit"
                                name="date_visit"
                                value="<?= $old['date_visit'] ?? $visit['date_visit'] ?? date('Y-m-d\TH:i') ?>">
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('date_visit') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="consultation_by" class="form-label">
                                Consulting Doctor <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= (isset($validation) && $validation->hasError('consultation_by')) ? 'is-invalid' : '' ?>"
                                id="consultation_by"
                                name="consultation_by">
                                <option value="">Select Doctor</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?= $doctor['id'] ?>" <?= ($old['consultation_by'] ?? $visit['consultation_by'] ?? '') == $doctor['id'] ? 'selected' : '' ?>>
                                        <?= esc($doctor['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('consultation_by') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="symptoms" class="form-label">
                                Symptoms <span class="text-danger">*</span>
                            </label>
                            <textarea
                                class="form-control <?= (isset($validation) && $validation->hasError('symptoms')) ? 'is-invalid' : '' ?>"
                                id="symptoms"
                                name="symptoms"
                                rows="3"
                                placeholder="Describe the symptoms"><?= $old['symptoms'] ?? $visit['symptoms'] ?? '' ?></textarea>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('symptoms') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="doctor_diagnose" class="form-label">
                                Doctor's Diagnosis <span class="text-danger">*</span>
                            </label>
                            <textarea
                                class="form-control <?= (isset($validation) && $validation->hasError('doctor_diagnose')) ? 'is-invalid' : '' ?>"
                                id="doctor_diagnose"
                                name="doctor_diagnose"
                                rows="3"
                                placeholder="Enter diagnosis"><?= $old['doctor_diagnose'] ?? $visit['doctor_diagnose'] ?? '' ?></textarea>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('doctor_diagnose') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="icd10_code" class="form-label">
                                ICD-10 Code <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= (isset($validation) && $validation->hasError('icd10_code')) ? 'is-invalid' : '' ?>"
                                id="icd10_select"
                                name="icd10_code"
                                autocomplete="off">
                                <?php if ($old['icd10_code'] ?? $visit['icd10_code'] ?? ''): ?>
                                    <option value="<?= $old['icd10_code'] ?? $visit['icd10_code'] ?>" selected><?= $old['icd10_code'] ?? $visit['icd10_code'] ?> - <?= $old['icd10_name'] ?? $visit['icd10_name'] ?></option>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('icd10_code') : '' ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="icd10_name" class="form-label">
                                ICD-10 Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= (isset($validation) && $validation->hasError('icd10_name')) ? 'is-invalid' : '' ?>"
                                id="icd10_name"
                                name="icd10_name"
                                value="<?= $old['icd10_name'] ?? $visit['icd10_name'] ?? '' ?>"
                                readonly>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('icd10_name') : '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="is_done" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select"
                                id="is_done"
                                name="is_done">
                                <option value="0" <?= ($old['is_done'] ?? $visit['is_done'] ?? '') ? 'selected' : '' ?>>
                                    Pending
                                </option>
                                <option value="1" <?= ($old['is_done'] ?? $visit['is_done'] ?? '') ? 'selected' : '' ?>>
                                    Done
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                <?= isset($validation) ? $validation->getError('is_done') : '' ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('medical-records/view/' . $patient['record_number']) ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- section script -->
<?= $this->section('script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#icd10_select', {
            valueField: 'code',
            labelField: 'name',
            searchField: ['code', 'name'],
            maxItems: 1,
            maxOptions: 50,
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div>' +
                        '<span class="fw-bold">' + escape(item.code) + '</span> - ' +
                        '<span class="text-muted">' + escape(item.name) + '</span>' +
                        '</div>';
                },
                item: function(item, escape) {
                    return '<div>' + escape(item.code) + ' - ' + escape(item.name) + '</div>';
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();

                fetch(`https://clinicaltables.nlm.nih.gov/api/icd10cm/v3/search?sf=code,name&terms=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(json => {
                        const results = json[3].map((item, index) => ({
                            code: item[0],
                            name: item[1]
                        }));
                        callback(results);
                    })
                    .catch(() => callback());
            },
            onChange: function(value) {
                const item = this.options[value];
                if (item) {
                    document.getElementById('icd10_name').value = item.name;
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>