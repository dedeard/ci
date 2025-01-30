<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Medical Records of Patients</h2>
        </div>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($patients)): ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Record Number</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Contact</th>
                        <th>Total Visits</th>
                        <th>Completed Visits</th>
                        <th>Pending Visits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= esc($patient['record_number']) ?></td>
                            <td><?= esc($patient['name']) ?></td>
                            <td><?= date('d M Y', strtotime($patient['birth'])) ?></td>
                            <td><?= esc($patient['phone']) ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?= esc($patient['total_visits'] ?? 0) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <?= esc($patient['total_completed'] ?? 0) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning">
                                    <?= esc(($patient['total_visits']) - ($patient['total_completed'] ?? 0)) ?>
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('medical-records/view/' . $patient['record_number']) ?>"
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="tooltip"
                                        title="View Medical Record">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                No patients found.
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>