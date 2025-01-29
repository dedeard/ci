<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!-- Main content -->
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Patients Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold text-primary small mb-1">Total Patients</div>
                            <div class="fs-4 fw-bold text-dark"><?= number_format($total_patients) ?></div>
                        </div>
                        <div class="text-secondary">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Visits Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold text-success small mb-1">Today's Visits</div>
                            <div class="fs-4 fw-bold text-dark"><?= number_format($todays_visits) ?></div>
                        </div>
                        <div class="text-secondary">
                            <i class="bi bi-calendar-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Consultations Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold text-warning small mb-1">Pending Consultations</div>
                            <div class="fs-4 fw-bold text-dark"><?= number_format($pending_consultations) ?></div>
                        </div>
                        <div class="text-secondary">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Doctors Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold text-info small mb-1">Total Doctors</div>
                            <div class="fs-4 fw-bold text-dark"><?= number_format($total_doctors) ?></div>
                        </div>
                        <div class="text-secondary">
                            <i class="bi bi-person-badge fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Top 5 Diagnoses -->
        <div class="col-xl-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h6 class="m-0 fw-bold text-primary">Top 5 Most Common Diagnoses</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ICD-10 Code</th>
                                    <th>Disease Name</th>
                                    <th class="text-end">Total Cases</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($top_diagnoses)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No diagnoses recorded yet</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($top_diagnoses as $diagnosis): ?>
                                        <tr>
                                            <td><?= esc($diagnosis['icd10_code']) ?></td>
                                            <td><?= esc($diagnosis['icd10_name']) ?></td>
                                            <td class="text-end"><?= number_format($diagnosis['total_cases']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h6 class="m-0 fw-bold text-primary">Recent Patients</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recent_patients)): ?>
                        <div class="p-4 text-center text-muted">No patients registered yet</div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_patients as $patient): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1"><?= esc($patient['name']) ?></h6>
                                        <small class="text-muted"><?= date('d M Y', strtotime($patient['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1 small text-muted">Record #: <?= esc($patient['record_number']) ?></p>
                                    <?php if (session()->get('role') === 'Doctor'): ?>
                                        <div class="mt-2">
                                            <a href="<?= base_url('medical-records/create/' . $patient['record_number']) ?>"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus-circle me-1"></i> Create Record
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Visits Chart -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h6 class="m-0 fw-bold text-primary">Patient Visits - Last 7 Days</h6>
                </div>
                <div class="card-body">
                    <canvas id="visitsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($visit_dates) ?>,
                datasets: [{
                    label: 'Number of Visits',
                    data: <?= json_encode($visit_counts) ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>