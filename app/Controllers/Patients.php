<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PatientModel;

class Patients extends Controller
{
    protected $patientModel;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Patients',
            'patients' => $this->patientModel->findAll()
        ];

        return view('patients/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'birth' => $this->request->getPost('birth'),
                'nik' => $this->request->getPost('nik'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'blood_type' => $this->request->getPost('blood_type'),
                'weight' => $this->request->getPost('weight'),
                'height' => $this->request->getPost('height')
            ];

            if ($this->patientModel->insert($data)) {
                return redirect()->to('/patients')
                    ->with('success', 'Patient created successfully');
            }
        }

        return view('patients/create', ['title' => 'Add Patient']);
    }

    public function update($id)
    {
        $patient = $this->patientModel->find($id);

        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'birth' => $this->request->getPost('birth'),
                'nik' => $this->request->getPost('nik'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'blood_type' => $this->request->getPost('blood_type'),
                'weight' => $this->request->getPost('weight'),
                'height' => $this->request->getPost('height')
            ];

            if ($this->patientModel->update($id, $data)) {
                return redirect()->to('/patients')
                    ->with('success', 'Patient updated successfully');
            }
        }

        $data = [
            'title' => 'Edit Patient',
            'patient' => $patient
        ];

        return view('patients/edit', $data);
    }

    public function delete($id)
    {
        if ($this->patientModel->delete($id)) {
            return redirect()->to('/patients')
                ->with('success', 'Patient deleted successfully');
        }
    }
}
