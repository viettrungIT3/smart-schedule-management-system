<?php

namespace App\Controllers\Api;

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
        $draw = $this->request->getGet('draw');
        $start = (int) ($this->request->getGet('start') ?? 0);
        $length = (int) ($this->request->getGet('length') ?? 10);
        $search = $this->request->getGet('search')['value'] ?? '';
        $orderColumn = $this->request->getGet('order')[0]['column'] ?? 0;
        $orderDir = $this->request->getGet('order')[0]['dir'] ?? 'asc';

        // Get column names for ordering
        $columns = ['id', 'full_name', 'email', 'role', 'status', 'last_login', 'created_at'];
        $orderBy = $columns[$orderColumn] ?? 'id';

        // Build query
        $builder = $this->userModel->select('
            users.*,
            GROUP_CONCAT(roles.name) as roles,
            GROUP_CONCAT(roles.id) as role_ids
        ')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->join('roles', 'roles.id = user_roles.role_id', 'left')
            ->groupBy('users.id');

        // Apply search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.full_name', $search)
                ->orLike('users.email', $search)
                ->orLike('roles.name', $search)
                ->groupEnd();
        }

        // Get total count
        $totalRecords = $this->userModel->countAllResults(false);

        // Apply ordering and pagination
        $users = $builder->orderBy($orderBy, $orderDir)
            ->limit($length, $start)
            ->findAll();

        // Format data for DataTables
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user['id'],
                'avatar' => base_url('assets/ablepro/images/user/avatar-1.jpg'),
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['roles'] ?? 'No Role',
                'status' => $user['status'],
                'last_login' => $user['last_login'],
                'created_at' => $user['created_at']
            ];
        }

        return $this->respond([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $user = $this->userModel->getUserWithRoles($id);

        if (!$user) {
            return $this->respond([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return $this->respond([
            'success' => true,
            'data' => $user
        ]);
    }

    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,teacher,student]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'status' => 'active'
        ];

        $userId = $this->userModel->insert($userData);

        if ($userId) {
            // Assign role to user
            $this->rbacService->assignRole($userId, $this->request->getPost('role'));

            return $this->respond([
                'success' => true,
                'message' => 'User created successfully',
                'data' => ['id' => $userId]
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to create user'
        ], 500);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->respond([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $rules = [
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role' => 'required|in_list[admin,teacher,student]',
            'status' => 'required|in_list[active,inactive]'
        ];

        // Add password rules only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'status' => $this->request->getPost('status')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $userData['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->update($id, $userData)) {
            // Update user role
            $this->rbacService->removeAllRoles($id);
            $this->rbacService->assignRole($id, $this->request->getPost('role'));

            return $this->respond([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update user'
        ], 500);
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
