<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Medical Records</h2>
        </div>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Record Number</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?= esc($patient['record_number']) ?></td>
                        <td><?= esc($patient['name']) ?></td>
                        <td><?= date('d M Y', strtotime($patient['birth'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $patient['blood_type'] === 'O' ? 'success' : ($patient['blood_type'] === 'AB' ? 'info' : ($patient['blood_type'] === 'B' ? 'warning' : 'danger')) ?>">
                                <?= esc($patient['blood_type']) ?>
                            </span>
                        </td>
                        <td><?= esc($patient['phone']) ?></td>
                        <td class="text-nowrap">
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('medical-records/view/' . $patient['record_number']) ?>"
                                    class="btn btn-sm btn-success"
                                    data-bs-toggle="tooltip"
                                    title="Show Medical Record">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>