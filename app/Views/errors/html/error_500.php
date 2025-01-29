<div class="auth-card card text-center">
    <div class="card-body p-5">
        <div class="mb-4">
            <i class="fas fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
        </div>

        <h1 class="display-5 mb-3">500</h1>
        <h4 class="text-muted mb-4">Internal Server Error</h4>

        <p class="text-secondary mb-4">
            Oops! Something went wrong on our server.
            Please try again later or contact support if the problem persists.
        </p>

        <a href="<?= base_url() ?>" class="btn btn-primary px-4">
            <i class="fas fa-home me-2"></i>
            Back to Home
        </a>

        <?php if (ENVIRONMENT !== 'production'): ?>
            <div class="mt-4 text-start">
                <div class="alert alert-danger">
                    <h5 class="alert-heading">Debug Information:</h5>
                    <hr>
                    <?php if (isset($message)): ?>
                        <p class="mb-0"><strong>Message:</strong> <?= nl2br(esc($message)) ?></p>
                    <?php endif; ?>

                    <?php if (isset($file)): ?>
                        <p class="mb-0"><strong>File:</strong> <?= esc($file) ?></p>
                        <p class="mb-0"><strong>Line:</strong> <?= esc($line) ?></p>
                    <?php endif; ?>

                    <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === true): ?>
                        <div class="mt-3">
                            <p class="mb-1"><strong>Backtrace:</strong></p>
                            <pre class="bg-light p-3 rounded small" style="max-height: 300px; overflow-y: auto;">
                                <?php foreach (debug_backtrace() as $error): ?>
                                    <?php if (isset($error['file'])): ?>
                                        <?= esc($error['file']) ?>:<?= esc($error['line']) ?><br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </pre>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>