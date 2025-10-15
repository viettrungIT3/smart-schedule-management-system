<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttendanceAndNotifications extends Migration
{
    public function up()
    {
        // attendance
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'schedule_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'student_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'status' => ['type' => 'ENUM','constraint' => ['present','absent','excused','late'],'default' => 'present'],
            'note' => ['type' => 'VARCHAR','constraint' => 255,'null' => true],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('attendance');
        $this->db->query('CREATE UNIQUE INDEX uq_attendance_unique ON attendance (schedule_id, student_id)');

        // notifications
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'user_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'title' => ['type' => 'VARCHAR','constraint' => 150],
            'message' => ['type' => 'TEXT'],
            'is_read' => ['type' => 'TINYINT','constraint' => 1,'default' => 0],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notifications');
    }

    public function down()
    {
        $this->forge->dropTable('notifications', true);
        $this->forge->dropTable('attendance', true);
    }
}


