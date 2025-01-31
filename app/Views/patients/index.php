<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Patients</h2>
            <?php if (session()->get('role') === 'Admin'): ?>
                <a href="<?= base_url('patients/create') ?>" class="btn btn-primary">Add New Patient</a>
            <?php endif; ?>
        </div>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>
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

        <!-- card -->

        <div class="card">
            <div class="card-body">
                <table id="patientTable" class="display">
                    <thead>
                        <tr>
                            <th>RN</th>
                            <th>Name</th>
                            <th>Birth Date</th>
                            <th>NIK</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Blood Type</th>
                            <th>Weight (kg)</th>
                            <th>Height (cm)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        $('#patientTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "/patients/datatables",
                type: 'POST'
            },
            columns: [{
                    data: 'record_number'
                },
                {
                    data: 'name'
                },
                {
                    data: 'birth'
                },
                {
                    data: 'nik'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'address'
                },
                {
                    data: 'blood_type',
                    render: function(data, type, row) {
                        let bgClass = 'success';
                        switch (data) {
                            case 'AB':
                                bgClass = 'info';
                                break;
                            case 'B':
                                bgClass = 'warning';
                                break;
                            case 'A':
                                bgClass = 'danger';
                                break;
                        }
                        return `<span class="badge bg-${bgClass}">${data}</span>`;
                    }
                },
                {
                    data: 'weight'
                },
                {
                    data: 'height'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let actions = '<div class="btn-group" role="group">';

                        <?php if (session()->get('role') === 'Admin'): ?>
                            // Edit button
                            actions += `
                            <a href="/patients/edit/${row.id}" 
                               class="btn btn-sm btn-primary" 
                               data-bs-toggle="tooltip" 
                               title="Edit Patient">
                                <i class="bi bi-pencil"></i>
                            </a>`;

                            // Delete button
                            actions += `
                            <button type="button"
                                class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal${row.id}"
                                title="Delete Patient">
                                <i class="bi bi-trash"></i>
                            </button>`;

                            // Delete modal
                            actions += `
                            <div class="modal fade" id="deleteModal${row.id}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete patient <strong>${row.name}</strong>?
                                            This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <a href="/patients/delete/${row.id}" class="btn btn-danger">Delete Patient</a>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        <?php endif; ?>

                        actions += '</div>';
                        return actions;
                    }
                }
            ],
            drawCallback: function() {
                // Reinitialize tooltips after table draw
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
<?= $this->endSection() ?>