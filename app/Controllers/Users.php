<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Users Controller
 * 
 * Handles all user management operations including:
 * - Listing users
 * - Creating new users
 * - Updating user details
 * - Deleting users
 * 
 * Note: Only accessible by Admin users
 */
class Users extends Controller
{
    /**
     * @var UserModel
     */
    protected UserModel $userModel;

    /**
     * Initialize controller
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Verify admin access
     *
     * @return bool|RedirectResponse
     */
    private function verifyAdminAccess()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Please login to access this page');
        }

        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')
                ->with('error', 'Access denied. Admin privileges required');
        }

        return true;
    }

    /**
     * Display list of users
     *
     * @return string|RedirectResponse
     */
    public function index()
    {
        $access = $this->verifyAdminAccess();
        if ($access !== true) {
            return $access;
        }

        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('users/index', $data);
    }

    /**
     * Create new user
     *
     * @return string|RedirectResponse
     */
    public function create()
    {
        $access = $this->verifyAdminAccess();
        if ($access !== true) {
            return $access;
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role' => 'required|in_list[Admin,Doctor]'
            ];

            if ($this->validate($rules)) {
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'role' => $this->request->getPost('role')
                ];

                try {
                    if ($this->userModel->insert($userData)) {
                        return redirect()->to('/users')
                            ->with('success', 'User created successfully');
                    }
                    throw new \Exception('Failed to create user');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error creating user: ' . $e->getMessage());
                }
            }
        }

        return view('users/create', [
            'title' => 'Create New User',
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Edit user details
     *
     * @param int|null $id User ID
     * @return string|RedirectResponse
     */
    public function edit(?int $id = null)
    {
        $access = $this->verifyAdminAccess();
        if ($access !== true) {
            return $access;
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email',
                'role' => 'required|in_list[Admin,Doctor]'
            ];

            // Add password validation if provided
            if ($password = $this->request->getPost('password')) {
                $rules['password'] = 'min_length[6]';
                $rules['password_confirm'] = 'matches[password]';
            }

            // Add unique email validation if changed
            if ($user['email'] !== $this->request->getPost('email')) {
                $rules['email'] .= '|is_unique[users.email]';
            }

            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'role' => $this->request->getPost('role')
                ];

                if ($password) {
                    $data['password'] = $password;
                }

                try {
                    if ($this->userModel->update($id, $data)) {
                        return redirect()->to('/users')
                            ->with('success', 'User updated successfully');
                    }
                    throw new \Exception('Failed to update user');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error updating user: ' . $e->getMessage());
                }
            }
        }

        return view('users/edit', [
            'title' => 'Edit User',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Delete user
     *
     * @param int|null $id User ID
     * @return RedirectResponse
     */
    public function delete(?int $id = null)
    {
        $access = $this->verifyAdminAccess();
        if ($access !== true) {
            return $access;
        }

        if ($id === (int)session()->get('id')) {
            return redirect()->to('/users')
                ->with('error', 'Cannot delete your own account');
        }

        if (!$this->userModel->find($id)) {
            return redirect()->to('/users')
                ->with('error', 'User not found');
        }

        try {
            if ($this->userModel->delete($id)) {
                return redirect()->to('/users')
                    ->with('success', 'User deleted successfully');
            }
            throw new \Exception('Failed to delete user');
        } catch (\Exception $e) {
            return redirect()->to('/users')
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
