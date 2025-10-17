<?php

namespace App\Services;

use App\Models\PermissionModel;
use App\Models\RoleModel;
use App\Models\RolePermissionModel;
use App\Models\UserRoleModel;

class RBACService
{
    protected $permissionModel;
    protected $roleModel;
    protected $rolePermissionModel;
    protected $userRoleModel;

    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
        $this->roleModel = new RoleModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->userRoleModel = new UserRoleModel();
    }

    /**
     * Check if user has specific permission
     *
     * @param int $userId
     * @param string $permission
     * @return bool
     */
    public function hasPermission(int $userId, string $permission): bool
    {
        $userRoles = $this->getUserRoles($userId);
        
        if (empty($userRoles)) {
            return false;
        }

        $roleIds = array_column($userRoles, 'role_id');
        
        $permissions = $this->permissionModel
            ->select('permissions.name')
            ->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->whereIn('role_permissions.role_id', $roleIds)
            ->findAll();

        $permissionNames = array_column($permissions, 'name');
        
        return in_array($permission, $permissionNames);
    }

    /**
     * Check if user has any of the specified permissions
     *
     * @param int $userId
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(int $userId, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($userId, $permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the specified permissions
     *
     * @param int $userId
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(int $userId, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($userId, $permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user has specific role
     *
     * @param int $userId
     * @param string $roleName
     * @return bool
     */
    public function hasRole(int $userId, string $roleName): bool
    {
        $userRoles = $this->getUserRoles($userId);
        
        if (empty($userRoles)) {
            return false;
        }

        $roleIds = array_column($userRoles, 'role_id');
        
        $roles = $this->roleModel
            ->whereIn('id', $roleIds)
            ->where('name', $roleName)
            ->findAll();

        return !empty($roles);
    }

    /**
     * Check if user has any of the specified roles
     *
     * @param int $userId
     * @param array $roleNames
     * @return bool
     */
    public function hasAnyRole(int $userId, array $roleNames): bool
    {
        $userRoles = $this->getUserRoles($userId);
        
        if (empty($userRoles)) {
            return false;
        }

        $roleIds = array_column($userRoles, 'role_id');
        
        $roles = $this->roleModel
            ->whereIn('id', $roleIds)
            ->whereIn('name', $roleNames)
            ->findAll();

        return !empty($roles);
    }

    /**
     * Get user roles
     *
     * @param int $userId
     * @return array
     */
    public function getUserRoles(int $userId): array
    {
        return $this->userRoleModel
            ->select('user_roles.*, roles.name as role_name, roles.display_name')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('user_roles.user_id', $userId)
            ->findAll();
    }

    /**
     * Get user permissions
     *
     * @param int $userId
     * @return array
     */
    public function getUserPermissions(int $userId): array
    {
        $userRoles = $this->getUserRoles($userId);
        
        if (empty($userRoles)) {
            return [];
        }

        $roleIds = array_column($userRoles, 'role_id');
        
        return $this->permissionModel
            ->select('permissions.*')
            ->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->whereIn('role_permissions.role_id', $roleIds)
            ->groupBy('permissions.id')
            ->findAll();
    }

    /**
     * Assign role to user
     *
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public function assignRole(int $userId, int $roleId): bool
    {
        // Check if assignment already exists
        $existing = $this->userRoleModel
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->first();

        if ($existing) {
            return true; // Already assigned
        }

        return $this->userRoleModel->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Remove role from user
     *
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public function removeRole(int $userId, int $roleId): bool
    {
        return $this->userRoleModel
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Assign permission to role
     *
     * @param int $roleId
     * @param int $permissionId
     * @return bool
     */
    public function assignPermissionToRole(int $roleId, int $permissionId): bool
    {
        // Check if assignment already exists
        $existing = $this->permissionModel
            ->select('role_permissions.id')
            ->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->where('role_permissions.role_id', $roleId)
            ->where('role_permissions.permission_id', $permissionId)
            ->first();

        if ($existing) {
            return true; // Already assigned
        }

        return $this->permissionModel->db->table('role_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);
    }

    /**
     * Remove permission from role
     *
     * @param int $roleId
     * @param int $permissionId
     * @return bool
     */
    public function removePermissionFromRole(int $roleId, int $permissionId): bool
    {
        return $this->permissionModel->db->table('role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete();
    }

    /**
     * Get all roles
     *
     * @return array
     */
    public function getAllRoles(): array
    {
        return $this->roleModel->findAll();
    }

    /**
     * Get all permissions
     *
     * @return array
     */
    public function getAllPermissions(): array
    {
        return $this->permissionModel->findAll();
    }

    /**
     * Get role by name
     *
     * @param string $roleName
     * @return array|null
     */
    public function getRoleByName(string $roleName): ?array
    {
        return $this->roleModel->where('name', $roleName)->first();
    }

    /**
     * Get permission by name
     *
     * @param string $permissionName
     * @return array|null
     */
    public function getPermissionByName(string $permissionName): ?array
    {
        return $this->permissionModel->where('name', $permissionName)->first();
    }

    /**
     * Check if user can access resource with action
     *
     * @param int $userId
     * @param string $resource
     * @param string $action
     * @return bool
     */
    public function canAccess(int $userId, string $resource, string $action): bool
    {
        $permission = $this->permissionModel
            ->where('resource', $resource)
            ->where('action', $action)
            ->first();

        if (!$permission) {
            return false;
        }

        return $this->hasPermission($userId, $permission['name']);
    }
}
