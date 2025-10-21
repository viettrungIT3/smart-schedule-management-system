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
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Validation
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters',
            'max_length' => 'First name cannot exceed 50 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters',
            'max_length' => 'Last name cannot exceed 50 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'required' => 'Password is required',
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
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
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
            ->like('first_name', $keyword)
            ->orLike('last_name', $keyword)
            ->orLike('email', $keyword)
            ->groupEnd()
            ->findAll();
    }
}
