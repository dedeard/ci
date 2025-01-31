<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PatientModel;
use CodeIgniter\HTTP\RedirectResponse;

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

    public function datatables()
    {
        // Get request parameters for DataTables
        $draw = $this->request->getPost('draw'); // Draw counter for DataTables
        $start = $this->request->getPost('start'); // Start record for pagination
        $length = $this->request->getPost('length'); // Number of records per page
        $searchValue = $this->request->getPost('search')['value']; // Search value
        $orderColumn = $this->request->getPost('order')[0]['column']; // Column index for sorting
        $orderDir = $this->request->getPost('order')[0]['dir']; // Sort direction (asc/desc)

        // Define column mappings for sorting
        $columns = [
            0 => 'record_number',
            1 => 'name',
            2 => 'birth',
            3 => 'nik',
            4 => 'phone',
            5 => 'address',
            6 => 'blood_type',
            7 => 'weight',
            8 => 'height'
        ];

        // Build the query with search and sort
        $builder = $this->patientModel;

        // Apply search filter
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('name', $searchValue)
                ->orLike('record_number', $searchValue)
                ->orLike('nik', $searchValue)
                ->orLike('phone', $searchValue)
                ->orLike('address', $searchValue)
                ->groupEnd();
        }

        // Apply sorting
        if (isset($columns[$orderColumn])) {
            $builder->orderBy($columns[$orderColumn], $orderDir);
        }

        // Get total records count (without pagination)
        $totalRecords = $builder->countAllResults(false);

        // Apply pagination
        $data = $builder->findAll($length, $start);

        // Prepare response for DataTables
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords, // Same as totalRecords if no search is applied
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            // Using validation rules from model
            if ($this->validate([
                'name' => 'required|min_length[3]|max_length[100]',
                'birth' => 'required|valid_date',
                'nik' => 'required|min_length[16]|max_length[20]|is_unique[patients.nik]',
                'phone' => 'required|min_length[10]|max_length[20]',
                'address' => 'required',
                'blood_type' => 'required|in_list[A,B,AB,O]',
                'weight' => 'required|numeric|greater_than[0]',
                'height' => 'required|numeric|greater_than[0]'
            ])) {
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

                try {
                    if ($this->patientModel->insert($data)) {
                        return redirect()->to('/patients')
                            ->with('success', 'Patient created successfully');
                    }
                    throw new \Exception('Failed to create patient');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error creating patient: ' . $e->getMessage());
                }
            }
        }

        // Initial form load
        return view('patients/form', [
            'title' => 'Add Patient',
            'old' => $this->request->getPost(),
            'validation' => \Config\Services::validation()
        ]);
    }

    public function edit(?int $id = null)
    {
        // Check if patient exists
        $patient = $this->patientModel->find($id);
        if (!$patient) {
            return redirect()->to('/patients')
                ->with('error', 'Patient not found');
        }

        if ($this->request->getMethod() === 'POST') {
            // Get current and new NIK
            $currentNik = $patient['nik'];
            $newNik = $this->request->getPost('nik');

            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'birth' => 'required|valid_date',
                'nik' => 'required|min_length[16]|max_length[20]',
                'phone' => 'required|min_length[10]|max_length[20]',
                'address' => 'required',
                'blood_type' => 'required|in_list[A,B,AB,O]',
                'weight' => 'required|numeric|greater_than[0]',
                'height' => 'required|numeric|greater_than[0]'
            ];

            // Only add unique validation if NIK is changed
            if ($currentNik !== $newNik) {
                $rules['nik'] .= "|is_unique[patients.nik,id,{$id}]";
            }

            if ($this->validate($rules)) {
                $birthDate = $this->request->getPost('birth');
                $formattedBirthDate = date('Y-m-d', strtotime($birthDate));

                $data = [
                    'name' => $this->request->getPost('name'),
                    'birth' => $formattedBirthDate,
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address'),
                    'blood_type' => $this->request->getPost('blood_type'),
                    'weight' => $this->request->getPost('weight'),
                    'height' => $this->request->getPost('height')
                ];

                // Only include NIK in update data if it changed
                if ($currentNik !== $newNik) {
                    $data['nik'] = $newNik;
                }
                try {

                    if ($this->patientModel->update($id, $data)) {
                        return redirect()->to('/patients')
                            ->with('success', 'Patient updated successfully');
                    }
                    throw new \Exception('Failed to update patient');
                } catch (\Exception $e) {

                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error updating patient: ' . $e->getMessage());
                }
            }
        }

        // Initial form load
        return view('patients/form', [
            'title' => 'Edit Patient',
            'patient' => $patient,
            'old' => $this->request->getPost(),
            'validation' => \Config\Services::validation()
        ]);
    }

    public function delete($id)
    {
        if ($this->patientModel->delete($id)) {
            return redirect()->to('/patients')
                ->with('success', 'Patient deleted successfully');
        }
    }
}
