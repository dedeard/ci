<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * User Model Class
 * 
 * Handles all database operations for users including:
 * - CRUD operations
 * - Password hashing
 * - User authentication
 * - Role-based queries
 */
class UserModel extends Model
{
    /**
     * Database & Model Configuration
     */
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $skipValidation   = false;

    /**
     * Allowed Fields for Mass Assignment
     */
    protected $allowedFields = [
        'email',
        'password',
        'name',
        'role'
    ];

    /**
     * Timestamps Configuration
     */
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Callbacks
     */
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash password before inserting/updating
     *
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data): array
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash(
            $data['data']['password'],
            PASSWORD_BCRYPT
        );

        return $data;
    }

    /**
     * Verify password against hash
     *
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Get all users by specific role
     *
     * @param string $role
     * @return array
     */
    public function getUsersByRole(string $role): array
    {
        return $this->where('role', $role)
            ->findAll();
    }
}
