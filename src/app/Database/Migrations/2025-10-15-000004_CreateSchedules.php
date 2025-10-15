<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedules extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'class_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'subject_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'teacher_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'room_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'timeslot_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'weekday' => ['type' => 'TINYINT','constraint' => 1], // 1-7
            'week' => ['type' => 'DATE','null' => true], // optional anchor week start
            'is_applied' => ['type' => 'TINYINT','constraint' => 1,'default' => 0],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('teacher_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('room_id', 'rooms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('timeslot_id', 'timeslots', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedules');
        // Unique composite to prevent conflicts per class/time
        $this->db->query('CREATE UNIQUE INDEX uq_class_timeslot_weekday ON schedules (class_id, weekday, timeslot_id)');
    }

    public function down()
    {
        $this->forge->dropTable('schedules', true);
    }
}


