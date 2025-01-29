<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Medical Record Details</h2>
            <?php if ($record['status'] !== 'Completed'): ?>
                <a href="<?= base_url('medical-records/complete/' . $record['id']) ?>" class="btn btn-success">Complete Record</a>
            <?php endif; ?>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Patient Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Record Number:</strong> <?= $record['record_number'] ?></p>
                        <p><strong>Name:</strong> <?= $record['patient_name'] ?></p>
                        <p><strong>Gender:</strong> <?= $record['gender'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date of Birth:</strong> <?= $record['date_of_birth'] ?></p>
                        <p><strong>Contact:</strong> <?= $record['contact_number'] ?></p>
                        <p><strong>Visit Date:</strong> <?= date('Y-m-d', strtotime($record['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Medical History</h5>
                <div class="mb-3">
                    <h6>Chief Complaint</h6>
                    <p><?= nl2br($record['chief_complaint']) ?></p>
                </div>
                <div class="mb-3">
                    <h6>History of Present Illness</h6>
                    <p><?= nl2br($record['present_illness']) ?></p>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Vital Signs</h5>
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Blood Pressure:</strong><br><?= $record['blood_pressure'] ?></p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Heart Rate:</strong><br><?= $record['heart_rate'] ?></p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Temperature:</strong><br><?= $record['temperature'] ?></p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Respiratory Rate:</strong><br><?= $record['respiratory_rate'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Examination & Assessment</h5>
                <div class="mb-3">
                    <h6>Physical Examination</h6>
                    <p><?= nl2br($record['physical_examination']) ?></p>
                </div>
                <div class="mb-3">
                    <h6>Assessment/Diagnosis</h6>
                    <p><?= nl2br($record['assessment']) ?></p>
                </div>
                <div class="mb-3">
                    <h6>Plan/Treatment</h6>
                    <p><?= nl2br($record['plan']) ?></p>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Record Information</h5>
                <p><strong>Status:</strong> <?= $record['status'] ?></p>
                <p><strong>Created By:</strong> <?= $record['created_by'] ?></p>
                <p><strong>Created At:</strong> <?= date('Y-m-d H:i:s', strtotime($record['created_at'])) ?></p>
                <?php if ($record['updated_at']): ?>
                    <p><strong>Last Updated:</strong> <?= date('Y-m-d H:i:s', strtotime($record['updated_at'])) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <a href="<?= base_url('medical-records') ?>" class="btn btn-secondary">Back to List</a>
        <?php if ($record['status'] !== 'Completed'): ?>
            <a href="<?= base_url('medical-records/edit/' . $record['id']) ?>" class="btn btn-primary">Edit Record</a>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>