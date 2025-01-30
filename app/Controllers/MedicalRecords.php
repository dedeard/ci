<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PatientHistoryModel;
use App\Models\PatientModel;
use App\Models\UserModel;

class MedicalRecords extends Controller
{
    protected $patientHistoryModel;
    protected $patientModel;
    protected $userModel;

    public function __construct()
    {
        $this->patientHistoryModel = new PatientHistoryModel();
        $this->patientModel = new PatientModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Doctor') {
            return redirect()->to('/dashboard');
        }


        $data = [
            'title' => 'Medical Records',
            'patients' => $this->patientModel->findAll()
        ];

        return view('medical-records/index', $data);
    }

    public function view($recordNumber)
    {
        $patient = $this->patientModel->where('record_number', $recordNumber)->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        $records = $this->patientHistoryModel->where('record_number', $recordNumber)->findAll();

        $data = [
            'title' => 'View Medical Records',
            'patient' => $patient,
            'records' => $records
        ];

        return view('medical-records/view', $data);
    }

    public function create($recordNumber = null)
    {
        // Check session and patient
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $patient = $this->patientModel->where('record_number', $recordNumber)->first();
        if (!$patient) {
            return redirect()->to('/medical-records')
                ->with('error', 'Patient not found');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'date_visit' => 'required|valid_date',
                'consultation_by' => 'required|numeric|is_not_unique[users.id]',
                'symptoms' => 'required|min_length[10]',
                'doctor_diagnose' => 'required|min_length[10]',
                'icd10_code' => 'required|max_length[20]',
                'icd10_name' => 'required|max_length[100]'
            ];

            if ($this->validate($rules)) {
                $visitData = [
                    'record_number' => $recordNumber,
                    'date_visit' => $this->request->getPost('date_visit'),
                    'registered_by' => session()->get('id'),
                    'consultation_by' => $this->request->getPost('consultation_by'),
                    'symptoms' => trim($this->request->getPost('symptoms')),
                    'doctor_diagnose' => trim($this->request->getPost('doctor_diagnose')),
                    'icd10_code' => trim($this->request->getPost('icd10_code')),
                    'icd10_name' => trim($this->request->getPost('icd10_name')),
                    'is_done' => $this->request->getPost('is_done') == '1' ? true : false
                ];

                if ($this->patientHistoryModel->insert($visitData)) {
                    return redirect()->to('/medical-records/view/' . $recordNumber)
                        ->with('success', 'New visit record created successfully');
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to create visit record');
            }

            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        // Get active doctors
        $doctors = $this->userModel->where('role', 'Doctor')->findAll();

        return view('medical-records/form', [
            'title' => 'Create New Visit',
            'patient' => $patient,
            'doctors' => $doctors,
            'old' => $this->request->getPost(),
            'validation' => \Config\Services::validation()
        ]);
    }


    public function edit($id)
    {
        $visit = $this->patientHistoryModel->find($id);

        if (!$visit) {
            return redirect()->back()->with('error', 'Visit not found');
        }

        if ($this->request->getMethod() === 'POST') {
            $validationRules = [
                'date_visit' => 'required|valid_date',
                'consultation_by' => 'required|numeric|is_not_unique[users.id]',
                'symptoms' => 'required|min_length[10]',
                'doctor_diagnose' => 'required|min_length[10]',
                'icd10_code' => 'required|max_length[20]',
                'icd10_name' => 'required|max_length[100]'
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()
                    ->with('error', 'Please check your input.')
                    ->withInput()
                    ->with('validation', $this->validator);
            }

            $data = [
                'date_visit' => $this->request->getPost('date_visit'),
                'registered_by' => session()->get('id'),
                'consultation_by' => $this->request->getPost('consultation_by'),
                'symptoms' => trim($this->request->getPost('symptoms')),
                'doctor_diagnose' => trim($this->request->getPost('doctor_diagnose')),
                'icd10_code' => trim($this->request->getPost('icd10_code')),
                'icd10_name' => trim($this->request->getPost('icd10_name')),
                'is_done' => $this->request->getPost('is_done') == '1' ? true : false
            ];

            if ($this->patientHistoryModel->update($id, $data)) {
                return redirect()->to('/medical-records/view/' . $visit['record_number'])
                    ->with('success', 'Medical record updated successfully');
            }

            return redirect()->back()
                ->with('error', 'Failed to update medical record')
                ->withInput();
        }


        // Get active doctors
        $doctors = $this->userModel->where('role', 'Doctor')->findAll();

        $patient = $this->patientModel->where('record_number', $visit['record_number'])->first();


        $data = [
            'title' => 'Update Medical Record',
            'visit' => $visit,
            'doctors' => $doctors,
            'patient' =>  $patient,
            'old' => $this->request->getPost(),
            'validation' => \Config\Services::validation()
        ];

        return view('medical-records/form', $data);
    }
}
