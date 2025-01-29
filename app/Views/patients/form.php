<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <h2><?= isset($patient) ? 'Edit Patient' : 'Register New Patient' ?></h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($patient) ? base_url('patients/edit/' . $patient['id']) : base_url('patients/create') ?>" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= isset($patient) ? $patient['name'] : set_value('name') ?>" required>
            </div>

            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= isset($patient) ? $patient['date_of_birth'] : set_value('date_of_birth') ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="Male" <?= (isset($patient) && $patient['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= (isset($patient) && $patient['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?= isset($patient) ? $patient['address'] : set_value('address') ?></textarea>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?= isset($patient) ? $patient['contact_number'] : set_value('contact_number') ?>" required>
            </div>

            <div class="form-group">
                <label for="emergency_contact">Emergency Contact</label>
                <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="<?= isset($patient) ? $patient['emergency_contact'] : set_value('emergency_contact') ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= base_url('patients') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>