<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\PatientHistoryModel;
use App\Libraries\WHO_ICD;

class MedicalRecords extends BaseController
{
    protected $patientModel;
    protected $historyModel;
    protected $icdApi;
    
    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->historyModel = new PatientHistoryModel();
        $this->icdApi = new WHO_ICD();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Medical Records',
            'records' => $this->historyModel
                ->select('patient_histories.*, patients.name as patient_name')
                ->join('patients', 'patients.record_number = patient_histories.record_number')
                ->orderBy('date_visit', 'DESC')
                ->findAll()
        ];

        return view('medical_records/index', $data);
    }

    public function create($recordNumber = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post') {
            $postData = $this->request->getPost();
            $postData['consultation_by'] = session()->get('id');
            $postData['registered_by'] = session()->get('id');
            $postData['date_visit'] = date('Y-m-d H:i:s');
            
            if ($this->validate($this->historyModel->validationRules)) {
                $this->historyModel->insert($postData);
                return redirect()->to('/medical-records')->with('success', 'Medical record created successfully');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $patient = null;
        if ($recordNumber) {
            $patient = $this->patientModel->where('record_number', $recordNumber)->first();
            if (!$patient) {
                return redirect()->to('/medical-records')->with('error', 'Patient not found');
            }
        }

        $data = [
            'title' => 'New Medical Record',
            'patient' => $patient,
            'patients' => $this->patientModel->findAll()
        ];

        return view('medical_records/create', $data);
    }

    public function view($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $record = $this->historyModel
            ->select('patient_histories.*, patients.name as patient_name')
            ->join('patients', 'patients.record_number = patient_histories.record_number')
            ->find($id);

        if (!$record) {
            return redirect()->to('/medical-records')->with('error', 'Record not found');
        }

        $data = [
            'title' => 'View Medical Record',
            'record' => $record
        ];

        return view('medical_records/view', $data);
    }

    public function searchICD10()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $query = $this->request->getGet('q');
        if (empty($query)) {
            return $this->response->setJSON(['error' => 'Query parameter is required']);
        }

        $results = $this->icdApi->searchICD10($query);
        return $this->response->setJSON($results);
    }

    public function getICD10Details($code)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $results = $this->icdApi->getICD10Details($code);
        return $this->response->setJSON($results);
    }

    public function complete($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $record = $this->historyModel->find($id);
        if (!$record) {
            return redirect()->to('/medical-records')->with('error', 'Record not found');
        }

        $this->historyModel->update($id, ['is_done' => true]);
        return redirect()->to('/medical-records')->with('success', 'Medical record marked as complete');
    }
}