<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\RBACService;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $rbacService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->rbacService = new RBACService();
    }

    /**
     * Show login form
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Process login
     */
    public function processLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please check your input and try again.');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Find user by email
        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Check if user is active
        if ($user['status'] !== 'active') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Your account is not active. Please contact administrator.');
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_name' => $user['full_name'],
            'user_role' => $user['role'],
            'logged_in' => true
        ];

        session()->set($sessionData);

        // Set remember me cookie if requested
        if ($remember) {
            // Set cookie for 30 days
            setcookie('remember_token', base64_encode($user['id']), time() + (30 * 24 * 60 * 60), '/');
        }

        // Redirect to dashboard
        return redirect()->to('/dashboard')
            ->with('success', 'Welcome back, ' . $user['full_name'] . '!');
    }

    /**
     * Show register form
     */
    public function register()
    {
        // Redirect if already logged in
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /**
     * Process registration
     */
    public function processRegister()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required|in_list[student,teacher]',
            'terms' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please check your input and try again.');
        }

        $data = [
            'full_name' => $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role' => $this->request->getPost('role'),
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert user
        if ($this->userModel->insert($data)) {
            $userId = $this->userModel->getInsertID();
            
            // Assign role to user
            $this->rbacService->assignRole($userId, $this->getRoleId($data['role']));

            return redirect()->to('/login')
                ->with('success', 'Registration successful! Please login with your credentials.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Clear session
        session()->destroy();
        
        // Clear remember me cookie
        setcookie('remember_token', '', time() - 3600, '/');

        return redirect()->to('/login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show profile
     */
    public function profile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/login');
        }

        // Get user roles and permissions
        $userRoles = $this->rbacService->getUserRoles($userId);
        $userPermissions = $this->rbacService->getUserPermissions($userId);

        $data = [
            'title' => 'Profile',
            'user' => $user,
            'roles' => $userRoles,
            'permissions' => $userPermissions
        ];

        return view('auth/profile', $data);
    }

    /**
     * Get role ID by name
     */
    private function getRoleId($roleName)
    {
        $role = $this->rbacService->getRoleByName($roleName);
        return $role ? $role['id'] : null;
    }
}
