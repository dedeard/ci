<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Authentication Controller
 * 
 * Handles user authentication including:
 * - Login
 * - Logout
 * - Session management
 */
class Auth extends BaseController
{
    /**
     * @var UserModel
     */
    protected UserModel $userModel;

    /**
     * Maximum failed login attempts before timeout
     */
    private const MAX_LOGIN_ATTEMPTS = 5;

    /**
     * Lock duration in minutes after max attempts
     */
    private const LOCKOUT_DURATION = 15;

    /**
     * Initialize controller
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display login page
     *
     * @return string|RedirectResponse
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Login',
            'validation' => Services::validation()
        ]);
    }

    /**
     * Handle login attempt
     *
     * @return RedirectResponse
     */
    public function attemptLogin(): RedirectResponse
    {
        // Validate request
        $rules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[100]'
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]'
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Check login attempts
        if ($this->isLockedOut($email)) {
            return redirect()->back()
                ->with('error', 'Too many failed attempts. Please try again later.');
        }

        // Attempt authentication
        $user = $this->userModel->where('email', $email)->first();

        if (!$this->isValidLogin($user, $password)) {
            $this->incrementLoginAttempts($email);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        // Clear login attempts on successful login
        $this->clearLoginAttempts($email);

        // Set session data
        $this->setUserSession($user);

        return redirect()->to('/dashboard')
            ->with('success', 'Welcome back, ' . esc($user['name']));
    }

    /**
     * Handle user logout
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        // Clear all session data
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'You have been successfully logged out');
    }

    /**
     * Check if the login credentials are valid
     *
     * @param array|null $user
     * @param string $password
     * @return bool
     */
    private function isValidLogin(?array $user, string $password): bool
    {
        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password']);
    }

    /**
     * Set user session data
     *
     * @param array $user
     * @return void
     */
    private function setUserSession(array $user): void
    {
        $sessionData = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role'],
            'isLoggedIn' => true,
            'lastActivity' => time()
        ];

        session()->set($sessionData);
    }

    /**
     * Check if user is locked out due to too many failed attempts
     *
     * @param string $email
     * @return bool
     */
    private function isLockedOut(string $email): bool
    {
        $attempts = session()->get('login_attempts_' . md5($email)) ?? 0;
        $lastAttempt = session()->get('last_attempt_' . md5($email)) ?? 0;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $lockoutExpires = $lastAttempt + (self::LOCKOUT_DURATION * 60);
            return time() < $lockoutExpires;
        }

        return false;
    }

    /**
     * Increment failed login attempts
     *
     * @param string $email
     * @return void
     */
    private function incrementLoginAttempts(string $email): void
    {
        $attempts = session()->get('login_attempts_' . md5($email)) ?? 0;
        session()->set('login_attempts_' . md5($email), $attempts + 1);
        session()->set('last_attempt_' . md5($email), time());
    }

    /**
     * Clear failed login attempts
     *
     * @param string $email
     * @return void
     */
    private function clearLoginAttempts(string $email): void
    {
        session()->remove('login_attempts_' . md5($email));
        session()->remove('last_attempt_' . md5($email));
    }
}
