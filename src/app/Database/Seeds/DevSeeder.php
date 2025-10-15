<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DevSeeder extends Seeder
{
    public function run()
    {
        // Làm sạch dữ liệu để seed lặp lại không lỗi
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        foreach ([
            'attendance',
            'notifications',
            'schedules',
            'teaching_assignments',
            'classes',
            'subjects',
            'rooms',
            'timeslots',
            'users',
        ] as $table) {
            // TRUNCATE nhanh và reset AUTO_INCREMENT
            $this->db->query("TRUNCATE TABLE `{$table}`");
        }
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // Users: 1 admin, 2 teachers, 3 students
        $users = [
            ['role' => 'admin', 'full_name' => 'Admin One', 'email' => 'admin@example.com', 'password_hash' => password_hash('Admin@123', PASSWORD_BCRYPT), 'status' => 'active', 'created_at' => Time::now()],
            ['role' => 'teacher', 'full_name' => 'Teacher A', 'email' => 'teacher.a@example.com', 'password_hash' => password_hash('Teacher@123', PASSWORD_BCRYPT), 'status' => 'active', 'created_at' => Time::now()],
            ['role' => 'teacher', 'full_name' => 'Teacher B', 'email' => 'teacher.b@example.com', 'password_hash' => password_hash('Teacher@123', PASSWORD_BCRYPT), 'status' => 'active', 'created_at' => Time::now()],
            ['role' => 'student', 'full_name' => 'Student 1', 'email' => 'student1@example.com', 'password_hash' => password_hash('Student@123', PASSWORD_BCRYPT), 'status' => 'active', 'created_at' => Time::now()],
            ['role' => 'student', 'full_name' => 'Student 2', 'email' => 'student2@example.com', 'password_hash' => password_hash('Student@123', PASSWORD_BCRYPT), 'status' => 'active', 'created_at' => Time::now()],
            ['role' => 'student', 'full_name' => 'Student 3', 'email' => 'student3@example.com', 'password_hash' => password_hash('Student@123', PASSWORD_BCRYPT), 'status' => 'active', 'created_at' => Time::now()],
        ];
        $this->db->table('users')->insertBatch($users);

        // Basic classes
        $this->db->table('classes')->insertBatch([
            ['name' => '10A1', 'homeroom_teacher_id' => 2, 'student_count' => 45],
            ['name' => '10A2', 'homeroom_teacher_id' => 3, 'student_count' => 43],
        ]);

        // Subjects
        $this->db->table('subjects')->insertBatch([
            ['name' => 'Toán'],
            ['name' => 'Vật Lý'],
            ['name' => 'Hóa Học'],
        ]);

        // Rooms
        $this->db->table('rooms')->insertBatch([
            ['name' => 'P101', 'function' => 'Lớp học chuẩn'],
            ['name' => 'Lab Lý', 'function' => 'Thí nghiệm'],
        ]);

        // Timeslots (ví dụ)
        $this->db->table('timeslots')->insertBatch([
            ['name' => 'Tiết 1', 'start_time' => '07:00:00', 'end_time' => '07:45:00'],
            ['name' => 'Tiết 2', 'start_time' => '07:55:00', 'end_time' => '08:40:00'],
        ]);

        // Teaching assignments
        $this->db->table('teaching_assignments')->insertBatch([
            ['teacher_id' => 2, 'class_id' => 1, 'subject_id' => 1, 'periods_per_week' => 4],
            ['teacher_id' => 3, 'class_id' => 2, 'subject_id' => 2, 'periods_per_week' => 3],
        ]);
    }
}


