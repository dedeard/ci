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
                                <a href="<?= base_url('medical-records/create/' . $patient['record_number']) ?>"
                                    class="btn btn-sm btn-success"
                                    data-bs-toggle="tooltip"
                                    title="New Visit">
                                    <i class="bi bi-plus-circle"></i>
                                </a>


                                <?php if (session()->get('role') === 'Admin'): ?>
                                    <a href="<?= base_url('patients/edit/' . $patient['id']) ?>"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="tooltip"
                                        title="Edit Patient">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal<?= $patient['id'] ?>"
                                        title="Delete Patient">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <?php if (session()->get('role') === 'Admin'): ?>
                                <div class="modal fade" id="deleteModal<?= $patient['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete patient <strong><?= esc($patient['name']) ?></strong>?
                                                This action cannot be undone.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <a href="<?= base_url('patients/delete/' . $patient['id']) ?>"
                                                    class="btn btn-danger">
                                                    Delete Patient
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>