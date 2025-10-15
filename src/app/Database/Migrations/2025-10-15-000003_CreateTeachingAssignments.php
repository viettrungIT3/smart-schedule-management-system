<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeachingAssignments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'teacher_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'class_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'subject_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true],
            'periods_per_week' => ['type' => 'INT','constraint' => 11,'default' => 0],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('teacher_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('teaching_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('teaching_assignments', true);
    }
}


