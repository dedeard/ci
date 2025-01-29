<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="m-0 font-weight-bold">Users</h5>
                    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
                        Create User
                    </a>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="fw-bold"><?= esc($user['name']) ?></div>
                                                    <div class="small text-muted"><?= esc($user['email']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $roleClass = match ($user['role']) {
                                                'Admin' => 'bg-danger',
                                                'Doctor' => 'bg-info',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $roleClass ?>">
                                                <?= esc($user['role']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                            <div class="small text-muted">
                                                <?= date('H:i', strtotime($user['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if (session()->get('id') !== $user['id']): ?>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('users/edit/' . $user['id']) ?>"
                                                        class="btn btn-warning"
                                                        data-bs-toggle="tooltip"
                                                        title="Edit User">
                                                        <i class="bi bi-pen"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-id="<?= $user['id'] ?>"
                                                        data-name="<?= esc($user['name']) ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-info">
                                                    <i class="bi bi-user me-1"></i> Current User
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user: <strong id="deleteUserName"></strong>?</p>
                <p class="mb-0 text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="GET">
                    <?= csrf_field() ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Page specific script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle delete modal
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                const button = event.relatedTarget;

                // Extract info from data-* attributes
                const userId = button.getAttribute('data-id');
                const userName = button.getAttribute('data-name');

                // Update the modal's content
                const userNameElement = deleteModal.querySelector('#deleteUserName');
                const deleteForm = deleteModal.querySelector('#deleteForm');

                if (userNameElement) userNameElement.textContent = userName;
                if (deleteForm) deleteForm.action = `<?= base_url('users/delete') ?>/${userId}`;

                // Hide tooltip when showing modal
                const tooltip = bootstrap.Tooltip.getInstance(button);
                if (tooltip) {
                    tooltip.hide();
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>