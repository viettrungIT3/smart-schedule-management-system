<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RBACSeeder extends Seeder
{
    public function run()
    {
        $this->seedPermissions();
        $this->seedRoles();
        $this->assignPermissionsToRoles();
        $this->assignRolesToUsers();
    }

    private function seedPermissions()
    {
        $permissions = [
            // User Management
            ['name' => 'users.create', 'description' => 'Create new users', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'users.read', 'description' => 'View users', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'users.update', 'description' => 'Update users', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'users.delete', 'description' => 'Delete users', 'resource' => 'users', 'action' => 'delete'],
            
            // Schedule Management
            ['name' => 'schedules.create', 'description' => 'Create schedules', 'resource' => 'schedules', 'action' => 'create'],
            ['name' => 'schedules.read', 'description' => 'View schedules', 'resource' => 'schedules', 'action' => 'read'],
            ['name' => 'schedules.update', 'description' => 'Update schedules', 'resource' => 'schedules', 'action' => 'update'],
            ['name' => 'schedules.delete', 'description' => 'Delete schedules', 'resource' => 'schedules', 'action' => 'delete'],
            
            // Teaching Assignments
            ['name' => 'assignments.create', 'description' => 'Create teaching assignments', 'resource' => 'assignments', 'action' => 'create'],
            ['name' => 'assignments.read', 'description' => 'View teaching assignments', 'resource' => 'assignments', 'action' => 'read'],
            ['name' => 'assignments.update', 'description' => 'Update teaching assignments', 'resource' => 'assignments', 'action' => 'update'],
            ['name' => 'assignments.delete', 'description' => 'Delete teaching assignments', 'resource' => 'assignments', 'action' => 'delete'],
            
            // Attendance Management
            ['name' => 'attendance.create', 'description' => 'Mark attendance', 'resource' => 'attendance', 'action' => 'create'],
            ['name' => 'attendance.read', 'description' => 'View attendance', 'resource' => 'attendance', 'action' => 'read'],
            ['name' => 'attendance.update', 'description' => 'Update attendance', 'resource' => 'attendance', 'action' => 'update'],
            ['name' => 'attendance.delete', 'description' => 'Delete attendance', 'resource' => 'attendance', 'action' => 'delete'],
            
            // Notifications
            ['name' => 'notifications.create', 'description' => 'Create notifications', 'resource' => 'notifications', 'action' => 'create'],
            ['name' => 'notifications.read', 'description' => 'View notifications', 'resource' => 'notifications', 'action' => 'read'],
            ['name' => 'notifications.update', 'description' => 'Update notifications', 'resource' => 'notifications', 'action' => 'update'],
            ['name' => 'notifications.delete', 'description' => 'Delete notifications', 'resource' => 'notifications', 'action' => 'delete'],
            
            // System Administration
            ['name' => 'system.admin', 'description' => 'Full system administration', 'resource' => 'system', 'action' => 'admin'],
            ['name' => 'system.config', 'description' => 'System configuration', 'resource' => 'system', 'action' => 'config'],
        ];

        $this->db->table('permissions')->insertBatch($permissions);
    }

    private function seedRoles()
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions'
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Teacher with limited administrative access'
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Student with read-only access to their data'
            ]
        ];

        $this->db->table('roles')->insertBatch($roles);
    }

    private function assignPermissionsToRoles()
    {
        // Get role IDs
        $adminRole = $this->db->table('roles')->where('name', 'admin')->get()->getRowArray();
        $teacherRole = $this->db->table('roles')->where('name', 'teacher')->get()->getRowArray();
        $studentRole = $this->db->table('roles')->where('name', 'student')->get()->getRowArray();

        // Get permission IDs
        $permissions = $this->db->table('permissions')->get()->getResultArray();
        $permissionMap = [];
        foreach ($permissions as $permission) {
            $permissionMap[$permission['name']] = $permission['id'];
        }

        $rolePermissions = [];

        // Admin gets all permissions
        foreach ($permissions as $permission) {
            $rolePermissions[] = [
                'role_id' => $adminRole['id'],
                'permission_id' => $permission['id']
            ];
        }

        // Teacher permissions
        $teacherPermissions = [
            'schedules.read', 'schedules.create', 'schedules.update',
            'assignments.read', 'assignments.create', 'assignments.update',
            'attendance.create', 'attendance.read', 'attendance.update',
            'notifications.read', 'notifications.create',
            'users.read' // Can view students
        ];
        foreach ($teacherPermissions as $permName) {
            if (isset($permissionMap[$permName])) {
                $rolePermissions[] = [
                    'role_id' => $teacherRole['id'],
                    'permission_id' => $permissionMap[$permName]
                ];
            }
        }

        // Student permissions
        $studentPermissions = [
            'schedules.read',
            'attendance.read',
            'notifications.read'
        ];
        foreach ($studentPermissions as $permName) {
            if (isset($permissionMap[$permName])) {
                $rolePermissions[] = [
                    'role_id' => $studentRole['id'],
                    'permission_id' => $permissionMap[$permName]
                ];
            }
        }

        $this->db->table('role_permissions')->insertBatch($rolePermissions);
    }

    private function assignRolesToUsers()
    {
        // Get role IDs
        $adminRole = $this->db->table('roles')->where('name', 'admin')->get()->getRowArray();
        $teacherRole = $this->db->table('roles')->where('name', 'teacher')->get()->getRowArray();
        $studentRole = $this->db->table('roles')->where('name', 'student')->get()->getRowArray();

        // Get users by their role field
        $users = $this->db->table('users')->get()->getResultArray();

        $userRoles = [];
        foreach ($users as $user) {
            $roleId = null;
            switch ($user['role']) {
                case 'admin':
                    $roleId = $adminRole['id'];
                    break;
                case 'teacher':
                    $roleId = $teacherRole['id'];
                    break;
                case 'student':
                    $roleId = $studentRole['id'];
                    break;
            }

            if ($roleId) {
                $userRoles[] = [
                    'user_id' => $user['id'],
                    'role_id' => $roleId
                ];
            }
        }

        $this->db->table('user_roles')->insertBatch($userRoles);
    }
}
