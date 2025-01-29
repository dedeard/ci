<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Patient History Model Class
 * 
 * Handles all database operations for patient visit history including:
 * - CRUD operations for patient visits
 * - Consultation tracking
 * - Visit statistics
 * - ICD-10 diagnosis reporting
 */
class PatientHistoryModel extends Model
{
    /**
     * Database & Model Configuration
     */
    protected $table            = 'patient_history';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $skipValidation   = false;

    /**
     * Allowed Fields for Mass Assignment
     */
    protected $allowedFields = [
        'record_number',
        'date_visit',
        'registered_by',
        'consultation_by',
        'symptoms',
        'doctor_diagnose',
        'icd10_code',
        'icd10_name',
        'is_done'
    ];

    /**
     * Timestamps Configuration
     */
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Validation Rules
     */
    protected $validationRules = [
        'record_number'    => 'required|integer|exists[patients.record_number]',
        'date_visit'       => 'required|valid_date',
        'registered_by'    => 'required|integer|exists[users.id]',
        'consultation_by'  => 'required|integer|exists[users.id]',
        'symptoms'         => 'required',
        'doctor_diagnose'  => 'required_with[icd10_code]',
        'icd10_code'      => 'permit_empty|max_length[20]',
        'icd10_name'      => 'permit_empty|max_length[100]',
        'is_done'         => 'required|in_list[0,1]'
    ];

    /**
     * Get complete patient history with related information
     *
     * @param string $recordNumber Patient record number
     * @return array
     */
    public function getPatientHistory(string $recordNumber): array
    {
        return $this->select('
                patient_history.*,
                patients.name as patient_name,
                u1.name as doctor_name,
                u2.name as registered_by_name
            ')
            ->join('patients', 'patients.record_number = patient_history.record_number')
            ->join('users u1', 'u1.id = patient_history.consultation_by')
            ->join('users u2', 'u2.id = patient_history.registered_by')
            ->where('patient_history.record_number', $recordNumber)
            ->orderBy('patient_history.date_visit', 'DESC')
            ->findAll();
    }

    /**
     * Get pending consultations for a specific doctor
     *
     * @param int $doctorId Doctor's user ID
     * @return array
     */
    public function getPendingConsultations(int $doctorId): array
    {
        return $this->select('
                patient_history.*,
                patients.name as patient_name
            ')
            ->join('patients', 'patients.record_number = patient_history.record_number')
            ->where('patient_history.consultation_by', $doctorId)
            ->where('patient_history.is_done', false)
            ->orderBy('patient_history.date_visit', 'ASC')
            ->findAll();
    }

    /**
     * Get top 5 most frequently used ICD-10 diagnoses
     *
     * @return array
     */
    public function getTopDiagnoses(): array
    {
        return $this->select('
                icd10_code,
                icd10_name,
                COUNT(*) as total_cases,
                COUNT(DISTINCT record_number) as unique_patients
            ')
            ->where('icd10_code IS NOT NULL')
            ->where('icd10_code !=', '')
            ->where('is_done', true)
            ->groupBy(['icd10_code', 'icd10_name'])
            ->orderBy('total_cases', 'DESC')
            ->limit(5)
            ->findAll();
    }

    /**
     * Get count of today's patient visits
     *
     * @return int
     */
    public function getTodayVisitsCount(): int
    {
        return $this->where('DATE(date_visit)', date('Y-m-d'))
            ->countAllResults();
    }

    /**
     * Get visit statistics by date range
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array
     */
    public function getVisitsByDateRange(string $startDate, string $endDate): array
    {
        return $this->select('DATE(date_visit) as visit_date, COUNT(*) as visit_count')
            ->where('date_visit >=', $startDate)
            ->where('date_visit <=', $endDate)
            ->groupBy('DATE(date_visit)')
            ->orderBy('date_visit', 'ASC')
            ->findAll();
    }

    /**
     * Get patient visit history by record number
     *
     * @param string $recordNumber Patient record number
     * @return array
     */
    public function getPatientVisitHistory(string $recordNumber): array
    {
        return $this->select('
                patient_history.*,
                u1.name as doctor_name
            ')
            ->join('users u1', 'u1.id = patient_history.consultation_by')
            ->where('record_number', $recordNumber)
            ->orderBy('date_visit', 'DESC')
            ->findAll();
    }
}
