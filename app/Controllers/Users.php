<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->findAll()
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = $this->userModel->validationRules;
            
            if ($this->validate($rules)) {
                $this->userModel->insert($this->request->getPost());
                return redirect()->to('/users')->with('success', 'User created successfully');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return view('users/create');
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = $this->userModel->validationRules;
            
            // Don't require password on update
            if (empty($this->request->getPost('password'))) {
                unset($rules['password']);
            }
            
            if ($this->validate($rules)) {
                $this->userModel->update($id, $this->request->getPost());
                return redirect()->to('/users')->with('success', 'User updated successfully');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];

        return view('users/edit', $data);
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'Admin') {
            return redirect()->to('/dashboard')->with('error', 'Access Denied');
        }

        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted successfully');
    }
}