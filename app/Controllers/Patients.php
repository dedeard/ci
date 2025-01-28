<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\PatientHistoryModel;

class Patients extends BaseController
{
    protected $patientModel;
    protected $historyModel;
    
    public function __construct()
    {
        $this->patientModel = new PatientModel();
        $this->historyModel = new PatientHistoryModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Patient Management',
            'patients' => $this->patientModel->findAll()
        ];

        return view('patients/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        if ($this->request->getMethod() === 'post') {
            if ($this->validate($this->patientModel->validationRules)) {
                $this->patientModel->insert($this->request->getPost());
                return redirect()->to('/patients')->with('success', 'Patient created successfully');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return view('patients/create');
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        $patient = $this->patientModel->find($id);
        
        if (!$patient) {
            return redirect()->to('/patients')->with('error', 'Patient not found');
        }

        if ($this->request->getMethod() === 'post') {
            if ($this->validate($this->patientModel->validationRules)) {
                $this->patientModel->update($id, $this->request->getPost());
                return redirect()->to('/patients')->with('success', 'Patient updated successfully');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => 'Edit Patient',
            'patient' => $patient
        ];

        return view('patients/edit', $data);
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        $this->patientModel->delete($id);
        return redirect()->to('/patients')->with('success', 'Patient deleted successfully');
    }
}