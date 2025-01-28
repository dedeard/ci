<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientHistoryModel extends Model
{
    protected $table = 'patient_histories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'record_number', 'date_visit', 'registered_by', 'consultation_by',
        'symptoms', 'doctor_diagnose', 'icd10_code', 'icd10_name', 'is_done'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'record_number' => 'required|exists[patients.record_number]',
        'date_visit' => 'required|valid_date',
        'registered_by' => 'required|integer|exists[users.id]',
        'consultation_by' => 'required|integer|exists[users.id]',
        'symptoms' => 'required',
        'doctor_diagnose' => 'required_with[icd10_code]',
        'icd10_code' => 'required_with[doctor_diagnose]',
        'icd10_name' => 'required_with[icd10_code]'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;

    // Get top 5 most used ICD-10 diagnoses
    public function getTopDiagnoses()
    {
        return $this->select('icd10_code, icd10_name, COUNT(*) as total_cases')
                    ->groupBy(['icd10_code', 'icd10_name'])
                    ->orderBy('total_cases', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
    }
}