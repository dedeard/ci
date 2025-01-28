<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'record_number', 'name', 'birth', 'nik', 'phone',
        'address', 'blood_type', 'weight', 'height'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'record_number' => 'required|is_unique[patients.record_number]',
        'name' => 'required|min_length[3]',
        'birth' => 'required|valid_date',
        'nik' => 'required|exact_length[16]|is_unique[patients.nik]',
        'phone' => 'required|min_length[10]',
        'address' => 'required',
        'blood_type' => 'permit_empty|in_list[A,B,AB,O]',
        'weight' => 'permit_empty|numeric',
        'height' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;

    protected $beforeInsert = ['generateRecordNumber'];
    
    protected function generateRecordNumber(array $data)
    {
        if (!isset($data['data']['record_number'])) {
            $year = date('Y');
            $month = date('m');
            $lastRecord = $this->like('record_number', "RM-$year$month-")
                              ->orderBy('record_number', 'DESC')
                              ->first();
            
            $sequence = '0001';
            if ($lastRecord) {
                $lastNumber = substr($lastRecord['record_number'], -4);
                $sequence = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
            
            $data['data']['record_number'] = "RM-$year$month-$sequence";
        }
        return $data;
    }
}