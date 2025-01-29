<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <h2><?= isset($user) ? 'Edit User' : 'Create New User' ?></h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($user) ? base_url('users/edit/' . $user['id']) : base_url('users/create') ?>" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= isset($user) ? $user['name'] : set_value('name') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? $user['email'] : set_value('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password <?= isset($user) ? '(leave blank to keep current)' : '' ?></label>
                <input type="password" class="form-control" id="password" name="password" <?= isset($user) ? '' : 'required' ?>>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="Admin" <?= (isset($user) && $user['role'] === 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="Staff" <?= (isset($user) && $user['role'] === 'Staff') ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Active" <?= (isset($user) && $user['status'] === 'Active') ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= (isset($user) && $user['status'] === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= base_url('users') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>