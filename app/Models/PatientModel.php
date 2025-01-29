<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

/**
 * Patient Model Class
 * 
 * Handles all database operations for patients including:
 * - CRUD operations
 * - Record number generation
 * - Age calculations
 * - Patient searches
 */
class PatientModel extends Model
{
    /**
     * Database & Model Configuration
     */
    protected $table            = 'patients';
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
        'name',
        'date_of_birth',
        'gender',
        'address',
        'contact_number',
        'emergency_contact'
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
        'record_number'     => 'required|max_length[20]|is_unique[patients.record_number]',
        'name'             => 'required|min_length[3]|max_length[100]',
        'date_of_birth'    => 'required|valid_date',
        'gender'           => 'required|in_list[Male,Female]',
        'address'          => 'required',
        'contact_number'   => 'required|max_length[20]',
        'emergency_contact' => 'required|max_length[100]'
    ];

    /**
     * Callbacks
     */
    protected $beforeInsert = ['generateRecordNumber'];

    /**
     * Generate unique record number for new patients
     *
     * @param array $data
     * @return array
     */
    protected function generateRecordNumber(array $data): array
    {
        if (!isset($data['data']['record_number'])) {
            $year = date('Y');
            $month = date('m');
            $prefix = "RM-{$year}{$month}-";

            $lastRecord = $this->like('record_number', $prefix)
                ->orderBy('record_number', 'DESC')
                ->first();

            $sequence = '0001';
            if ($lastRecord) {
                $lastNumber = substr($lastRecord['record_number'], -4);
                $sequence = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }

            $data['data']['record_number'] = $prefix . $sequence;
        }

        return $data;
    }

    /**
     * Get patient by record number
     *
     * @param string $recordNumber
     * @return array|null
     */
    public function getByRecordNumber(string $recordNumber): ?array
    {
        return $this->where('record_number', $recordNumber)
            ->first();
    }

    /**
     * Get patients within specified age range
     *
     * @param int $minAge Minimum age
     * @param int $maxAge Maximum age
     * @return array
     */
    public function getByAgeRange(int $minAge, int $maxAge): array
    {
        $minDate = date('Y-m-d', strtotime("-$maxAge years"));
        $maxDate = date('Y-m-d', strtotime("-$minAge years"));

        return $this->where('date_of_birth >=', $minDate)
            ->where('date_of_birth <=', $maxDate)
            ->findAll();
    }

    /**
     * Search patients by name, record number, or contact number
     *
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchPatients(string $keyword): array
    {
        return $this->like('name', $keyword)
            ->orLike('record_number', $keyword)
            ->orLike('contact_number', $keyword)
            ->findAll();
    }

    /**
     * Calculate patient's age from date of birth
     *
     * @param string $dateOfBirth Date of birth in Y-m-d format
     * @return int
     */
    public function calculateAge(string $dateOfBirth): int
    {
        $birthDate = new DateTime($dateOfBirth);
        $today = new DateTime('today');

        return (int)$birthDate->diff($today)->y;
    }
}
