<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Patients</h2>
            <?php if(session()->get('role') === 'Admin'): ?>
                <a href="<?= base_url('patients/create') ?>" class="btn btn-primary">Add New Patient</a>
            <?php endif; ?>
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
                    <td><?= $patient['record_number'] ?></td>
                    <td><?= $patient['name'] ?></td>
                    <td><?= $patient['date_of_birth'] ?></td>
                    <td><?= $patient['gender'] ?></td>
                    <td><?= $patient['contact_number'] ?></td>
                    <td>
                        <a href="<?= base_url('medical-records/create/' . $patient['record_number']) ?>" class="btn btn-sm btn-success">New Visit</a>
                        <?php if(session()->get('role') === 'Admin'): ?>
                            <a href="<?= base_url('patients/edit/' . $patient['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="<?= base_url('patients/delete/' . $patient['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>