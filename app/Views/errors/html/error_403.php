<?= $this->extend('layouts/guest') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mt-5">
                <h1 class="display-1">403</h1>
                <h2>Access Forbidden</h2>
                <p class="lead">You don't have permission to access this resource.</p>
                <a href="<?= base_url() ?>" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>