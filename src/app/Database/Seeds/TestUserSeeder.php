<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Create test users with known passwords for testing
        $testUsers = [
            [
                'role' => 'admin',
                'full_name' => 'Test Admin',
                'email' => 'admin@example.com',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'role' => 'teacher',
                'full_name' => 'Test Teacher',
                'email' => 'teacher.a@example.com',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'role' => 'student',
                'full_name' => 'Test Student',
                'email' => 'student1@example.com',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Clear existing test users
        $this->db->table('users')->whereIn('email', ['admin@example.com', 'teacher.a@example.com', 'student1@example.com'])->delete();

        // Insert test users
        $this->db->table('users')->insertBatch($testUsers);

        echo "Test users created with password: admin123\n";
    }
}
