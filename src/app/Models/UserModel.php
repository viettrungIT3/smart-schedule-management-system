<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'role',
        'full_name',
        'email',
        'password_hash',
        'status',
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Validation
    protected $validationRules = [
        'full_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[6]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'full_name' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must be at least 2 characters',
            'max_length' => 'Full name cannot exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'min_length' => 'Password must be at least 6 characters'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be either active or inactive'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['mapAndHashPassword'];
    protected $beforeUpdate = ['mapAndHashPassword'];

    protected function mapAndHashPassword(array $data)
    {
        if (isset($data['data']['password']) && $data['data']['password'] !== '') {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
        }
        return $data;
    }

    public function getUsersWithRoles()
    {
        return $this->select('
            users.*,
            GROUP_CONCAT(roles.name) as roles,
            GROUP_CONCAT(roles.id) as role_ids
        ')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->join('roles', 'roles.id = user_roles.role_id', 'left')
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();
    }

    public function getUserWithRoles($id)
    {
        return $this->select('
            users.*,
            GROUP_CONCAT(roles.name) as roles,
            GROUP_CONCAT(roles.id) as role_ids,
            GROUP_CONCAT(permissions.name) as permissions
        ')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->join('roles', 'roles.id = user_roles.role_id', 'left')
            ->join('role_permissions', 'role_permissions.role_id = roles.id', 'left')
            ->join('permissions', 'permissions.id = role_permissions.permission_id', 'left')
            ->where('users.id', $id)
            ->groupBy('users.id')
            ->first();
    }

    public function getUsersByRole($role)
    {
        return $this->select('users.*')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('roles.name', $role)
            ->where('users.status', 'active')
            ->findAll();
    }

    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function updateLastLogin($id)
    {
        return $this->update($id, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }

    public function searchUsers($keyword)
    {
        return $this->groupStart()
            ->like('full_name', $keyword)
            ->orLike('email', $keyword)
            ->groupEnd()
            ->findAll();
    }
}
