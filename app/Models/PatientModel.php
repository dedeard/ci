<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $skipValidation   = false;

    protected $allowedFields = [
        'record_number',
        'name',
        'birth',
        'nik',
        'phone',
        'address',
        'blood_type',
        'weight',
        'height'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'birth' => 'required|valid_date',
        'nik' => 'required|min_length[16]|max_length[20]|is_unique[patients.nik]',
        'phone' => 'required|min_length[10]|max_length[20]',
        'address' => 'required',
        'blood_type' => 'required|in_list[A,B,AB,O]',
        'weight' => 'required|numeric|greater_than[0]',
        'height' => 'required|numeric|greater_than[0]'
    ];


    protected $beforeInsert = ['generateRecordNumber'];

    protected function generateRecordNumber(array $data): array
    {
        if (!isset($data['data']['record_number'])) {
            // Get the last record number
            $lastRecord = $this->orderBy('record_number', 'DESC')->first();

            // Generate new record number
            $nextNumber = $lastRecord ? ($lastRecord['record_number'] + 1) : 1;

            $data['data']['record_number'] = $nextNumber;
        }
        return $data;
    }
}
