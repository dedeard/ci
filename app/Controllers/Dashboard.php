<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    private $patientHistoryModel;
    private $patientModel;
    private $userModel;

    public function __construct()
    {
        // Load the models
        $this->patientHistoryModel = model('PatientHistoryModel');
        $this->patientModel = model('PatientModel');
        $this->userModel = model('UserModel');
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Basic statistics
        $data = [
            'title' => 'Dashboard',
            'total_patients' => $this->patientModel->countAll(),
            'todays_visits' => $this->patientHistoryModel
                ->where('DATE(date_visit)', date('Y-m-d'))
                ->countAllResults(),
            'pending_consultations' => $this->patientHistoryModel
                ->where('is_done', false)
                ->countAllResults(),
            'total_doctors' => $this->userModel
                ->where('role', 'Doctor')
                ->countAllResults()
        ];



        // Recent patients
        $data['recent_patients'] = $this->patientModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Top diagnoses
        $data['top_diagnoses'] = $this->patientHistoryModel->getTopDiagnoses();

        // Weekly visits data
        $visit_dates = [];
        $visit_counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $visit_dates[] = date('d M', strtotime($date));
            $count = $this->patientHistoryModel
                ->where('DATE(date_visit)', $date)
                ->countAllResults();
            $visit_counts[] = $count;
        }
        $data['visit_dates'] = $visit_dates;
        $data['visit_counts'] = $visit_counts;


        // Return view with data
        return view('dashboard/index', $data);
    }
}
