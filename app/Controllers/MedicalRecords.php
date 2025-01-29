<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PatientHistoryModel;
use App\Models\PatientModel;

class MedicalRecord extends BaseController
{
    protected $patientHistoryModel;
    protected $patientModel;

    public function __construct()
    {
        $this->patientHistoryModel = new PatientHistoryModel();
        $this->patientModel = new PatientModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Doctor') {
            return redirect()->to('/dashboard');
        }

        // Get filter parameter
        $filter = $this->request->getGet('filter');
        $doctor_id = session()->get('user_id');

        // Get records based on filter
        if ($filter === 'pending') {
            $records = $this->patientHistoryModel->where('consultation_by', $doctor_id)
                ->where('is_done', false)
                ->findAll();
        } else {
            $records = $this->patientHistoryModel->where('consultation_by', $doctor_id)
                ->findAll();
        }

        $data = [
            'title' => 'Medical Records',
            'records' => $records,
            'filter' => $filter
        ];

        return view('medical_records/index', $data);
    }

    public function create($recordNumber = null)
    {
        if (!$recordNumber) {
            return redirect()->back()->with('error', 'Record number is required');
        }

        if ($this->request->getMethod() === 'post') {
            $record = [
                'record_number' => $recordNumber,
                'date_visit' => date('Y-m-d H:i:s'),
                'symptoms' => $this->request->getPost('symptoms'),
                'doctor_diagnose' => $this->request->getPost('doctor_diagnose'),
                'icd10_code' => $this->request->getPost('icd10_code'),
                'icd10_name' => $this->request->getPost('icd10_name'),
                'consultation_by' => session()->get('user_id'),
                'registered_by' => session()->get('user_id'),
                'is_done' => false
            ];

            if ($this->patientHistoryModel->insert($record)) {
                return redirect()->to('/medical-records')
                    ->with('success', 'Medical record created successfully');
            }

            return redirect()->back()
                ->with('error', 'Failed to create medical record')
                ->withInput();
        }

        $data = [
            'title' => 'Create Medical Record',
            'patient' => $this->patientModel->where('record_number', $recordNumber)->first()
        ];

        if (empty($data['patient'])) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        return view('medical_records/create', $data);
    }

    public function update($id)
    {
        $record = $this->patientHistoryModel->find($id);

        if (!$record) {
            return redirect()->back()->with('error', 'Record not found');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'symptoms' => $this->request->getPost('symptoms'),
                'doctor_diagnose' => $this->request->getPost('doctor_diagnose'),
                'icd10_code' => $this->request->getPost('icd10_code'),
                'icd10_name' => $this->request->getPost('icd10_name'),
                'is_done' => $this->request->getPost('is_done') ? true : false
            ];

            if ($this->patientHistoryModel->update($id, $data)) {
                return redirect()->to('/medical-records')
                    ->with('success', 'Medical record updated successfully');
            }

            return redirect()->back()
                ->with('error', 'Failed to update medical record')
                ->withInput();
        }

        $data = [
            'title' => 'Update Medical Record',
            'record' => $record,
            'patient' => $this->patientModel->where('record_number', $record['record_number'])->first()
        ];

        return view('medical_records/edit', $data);
    }

    public function searchICD()
    {
        $term = $this->request->getGet('term');

        if (empty($term)) {
            return $this->response->setJSON([]);
        }

        try {
            // Call WHO ICD API here
            $client = \Config\Services::curlrequest();
            $response = $client->request('GET', 'https://id.who.int/icdapi/entity/search', [
                'headers' => [
                    'Accept' => 'application/json',
                    'API-Version' => 'v2',
                    'Accept-Language' => 'en'
                ],
                'query' => [
                    'q' => $term,
                    'releaseId' => '2023-01'
                ]
            ]);

            return $this->response->setJSON(json_decode($response->getBody()));
        } catch (\Exception $e) {
            log_message('error', 'ICD API Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Failed to fetch ICD codes. Please try again later.'
            ])->setStatusCode(500);
        }
    }

    public function getPendingPatients()
    {
        $doctor_id = session()->get('user_id');
        $records = $this->patientHistoryModel->where('consultation_by', $doctor_id)
            ->where('is_done', false)
            ->findAll();

        return $this->response->setJSON($records);
    }
}
