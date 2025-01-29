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

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= base_url('medical-records') ?>" method="get" class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?= $search ?? '' ?>" placeholder="Record number or patient name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All</option>
                                <option value="Pending" <?= ($status ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Completed" <?= ($status ?? '') === 'Completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="<?= $date_from ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="<?= $date_to ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="<?= base_url('medical-records') ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Record Number</th>
                    <th>Patient Name</th>
                    <th>Visit Date</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr>
                    <td><?= $record['record_number'] ?></td>
                    <td><?= $record['patient_name'] ?></td>
                    <td><?= date('Y-m-d', strtotime($record['created_at'])) ?></td>
                    <td><?= $record['status'] ?></td>
                    <td><?= $record['created_by'] ?></td>
                    <td>
                        <a href="<?= base_url('medical-records/view/' . $record['id']) ?>" class="btn btn-sm btn-info">View</a>
                        <?php if ($record['status'] !== 'Completed'): ?>
                            <a href="<?= base_url('medical-records/edit/' . $record['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="<?= base_url('medical-records/complete/' . $record['id']) ?>" class="btn btn-sm btn-success">Complete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>