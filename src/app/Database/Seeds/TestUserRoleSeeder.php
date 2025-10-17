<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestUserRoleSeeder extends Seeder
{
    public function run()
    {
        // Assign roles to test users
        $this->assignRolesToTestUsers();
    }

    private function assignRolesToTestUsers()
    {
        // Get role IDs
        $adminRole = $this->db->table('roles')->where('name', 'admin')->get()->getRowArray();
        $teacherRole = $this->db->table('roles')->where('name', 'teacher')->get()->getRowArray();
        $studentRole = $this->db->table('roles')->where('name', 'student')->get()->getRowArray();

        // Get test users
        $testUsers = $this->db->table('users')
            ->whereIn('email', ['admin@example.com', 'teacher.a@example.com', 'student1@example.com'])
            ->get()
            ->getResultArray();

        $userRoles = [];
        foreach ($testUsers as $user) {
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
                // Check if role already assigned
                $existing = $this->db->table('user_roles')
                    ->where('user_id', $user['id'])
                    ->where('role_id', $roleId)
                    ->get()
                    ->getRowArray();

                if (!$existing) {
                    $userRoles[] = [
                        'user_id' => $user['id'],
                        'role_id' => $roleId
                    ];
                }
            }
        }

        if (!empty($userRoles)) {
            $this->db->table('user_roles')->insertBatch($userRoles);
            echo "Assigned roles to " . count($userRoles) . " test users\n";
        } else {
            echo "All test users already have roles assigned\n";
        }
    }
}
