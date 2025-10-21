<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\RBACService;

class UsersController extends BaseController
{
    protected $userModel;
    protected $rbacService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->rbacService = new RBACService();
    }

    public function index()
    {
        $data = [
            'title' => 'User Management - ScheduleFlow',
            'users' => $this->userModel->getUsersWithRoles()
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New User - ScheduleFlow',
            'roles' => $this->rbacService->getAllRoles()
        ];

        return view('users/create', $data);
    }

    public function store()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[admin,teacher,student]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $userId = $this->userModel->insert($userData);

        if ($userId) {
            // Assign role to user
            $this->rbacService->assignRole($userId, $this->request->getPost('role'));

            return redirect()->to('/users')
                ->with('success', 'User created successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create user');
    }

    public function show($id)
    {
        $user = $this->userModel->getUserWithRoles($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        $data = [
            'title' => 'User Details - ScheduleFlow',
            'user' => $user
        ];

        return view('users/show', $data);
    }

    public function edit($id)
    {
        $user = $this->userModel->getUserWithRoles($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        $data = [
            'title' => 'Edit User - ScheduleFlow',
            'user' => $user,
            'roles' => $this->rbacService->getAllRoles()
        ];

        return view('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role' => 'required|in_list[admin,teacher,student]',
            'status' => 'required|in_list[active,inactive]'
        ];

        // Add password rules only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $userData)) {
            // Update user role
            $this->rbacService->removeAllRoles($id);
            $this->rbacService->assignRole($id, $this->request->getPost('role'));

            return redirect()->to('/users')
                ->with('success', 'User updated successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update user');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->respond([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Check if user is trying to delete themselves
        if ($id == session()->get('user_id')) {
            return $this->respond([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ], 400);
        }

        if ($this->userModel->delete($id)) {
            return $this->respond([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to delete user'
        ], 500);
    }
}
