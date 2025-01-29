<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <h2><?= isset($record) ? 'Edit Medical Record' : 'Create Medical Record' ?></h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= isset($record) ? base_url('medical-records/edit/' . $record['id']) : base_url('medical-records/create') ?>" method="post">
            <?php if (isset($patient)): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Patient Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Record Number:</strong> <?= $patient['record_number'] ?></p>
                            <p><strong>Name:</strong> <?= $patient['name'] ?></p>
                            <p><strong>Gender:</strong> <?= $patient['gender'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> <?= $patient['date_of_birth'] ?></p>
                            <p><strong>Contact:</strong> <?= $patient['contact_number'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="chief_complaint">Chief Complaint</label>
                <textarea class="form-control" id="chief_complaint" name="chief_complaint" rows="3" required><?= isset($record) ? $record['chief_complaint'] : set_value('chief_complaint') ?></textarea>
            </div>

            <div class="form-group">
                <label for="present_illness">History of Present Illness</label>
                <textarea class="form-control" id="present_illness" name="present_illness" rows="3" required><?= isset($record) ? $record['present_illness'] : set_value('present_illness') ?></textarea>
            </div>

            <div class="form-group">
                <label for="vital_signs">Vital Signs</label>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="blood_pressure" placeholder="Blood Pressure" value="<?= isset($record) ? $record['blood_pressure'] : set_value('blood_pressure') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="heart_rate" placeholder="Heart Rate" value="<?= isset($record) ? $record['heart_rate'] : set_value('heart_rate') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="temperature" placeholder="Temperature" value="<?= isset($record) ? $record['temperature'] : set_value('temperature') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="respiratory_rate" placeholder="Respiratory Rate" value="<?= isset($record) ? $record['respiratory_rate'] : set_value('respiratory_rate') ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="physical_examination">Physical Examination</label>
                <textarea class="form-control" id="physical_examination" name="physical_examination" rows="3" required><?= isset($record) ? $record['physical_examination'] : set_value('physical_examination') ?></textarea>
            </div>

            <div class="form-group">
                <label for="assessment">Assessment/Diagnosis</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="icd10_search" placeholder="Search ICD-10 codes...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="searchICD10()">Search</button>
                    </div>
                </div>
                <div id="icd10_results" class="mb-2"></div>
                <textarea class="form-control" id="assessment" name="assessment" rows="3" required><?= isset($record) ? $record['assessment'] : set_value('assessment') ?></textarea>
            </div>

            <div class="form-group">
                <label for="plan">Plan/Treatment</label>
                <textarea class="form-control" id="plan" name="plan" rows="3" required><?= isset($record) ? $record['plan'] : set_value('plan') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= base_url('medical-records') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
function searchICD10() {
    const query = document.getElementById('icd10_search').value;
    fetch(`<?= base_url('medical-records/search-icd10') ?>?q=${query}`)
        .then(response => response.json())
        .then(data => {
            const resultsDiv = document.getElementById('icd10_results');
            resultsDiv.innerHTML = '';
            data.forEach(item => {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm btn-outline-primary mr-2 mb-2';
                btn.textContent = `${item.code} - ${item.description}`;
                btn.onclick = function() {
                    const assessment = document.getElementById('assessment');
                    assessment.value += `\nICD-10: ${item.code} - ${item.description}`;
                };
                resultsDiv.appendChild(btn);
            });
        });
}
</script>
<?= $this->endSection() ?>