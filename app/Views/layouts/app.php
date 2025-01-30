<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records System</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <style>
        .sidebar {
            min-height: 100vh;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(0, 0, 0, .05);
        }

        .sidebar .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, .1);
        }

        .content {
            padding: 20px;
        }

        .navbar-brand {
            padding: 1rem 1.25rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .user-info {
            padding: 1rem;
            border-top: 1px solid rgba(0, 0, 0, .1);
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="sidebarMenu">
                <div class="position-sticky">
                    <a class="navbar-brand d-flex align-items-center" href="<?= base_url('dashboard') ?>">
                        <i class="bi bi-hospital me-2"></i>
                        Medical Records
                    </a>

                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <a class="nav-link <?= url_is('dashboard*') ? 'active' : '' ?>"
                                href="<?= base_url('dashboard') ?>">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>

                        <?php if (session()->get('role') === 'Admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= url_is('patients*') ? 'active' : '' ?>"
                                    href="<?= base_url('patients') ?>">
                                    <i class="bi bi-people"></i>
                                    Patients
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (session()->get('role') === 'Doctor'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= url_is('medical-records*') ? 'active' : '' ?>"
                                    href="<?= base_url('medical-records') ?>">
                                    <i class="bi bi-journal-medical"></i>
                                    Medical Records
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (session()->get('role') === 'Admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= url_is('users*') ? 'active' : '' ?>"
                                    href="<?= base_url('users') ?>">
                                    <i class="bi bi-person-gear"></i>
                                    Users
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <!-- User Info -->
                    <div class="user-info mt-auto">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= esc(session()->get('name')) ?></div>
                                <small class="text-muted"><?= esc(session()->get('role')) ?></small>
                            </div>
                        </div>
                        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Mobile Toggle -->
                <nav class="navbar navbar-light d-md-none bg-light mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <span class="navbar-brand">Medical Records</span>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="py-3">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!-- Initialize active states -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>


    <?= $this->renderSection('script') ?>
</body>

</html>