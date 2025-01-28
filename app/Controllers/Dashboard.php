<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\PatientHistoryModel;

class Dashboard extends BaseController
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
            'title' => 'Dashboard',
            'totalPatients' => $this->patientModel->countAll(),
            'todayVisits' => $this->historyModel->where('DATE(date_visit)', date('Y-m-d'))->countAllResults(),
            'topDiagnoses' => $this->historyModel->getTopDiagnoses(),
        ];

        return view('dashboard/index', $data);
    }
}