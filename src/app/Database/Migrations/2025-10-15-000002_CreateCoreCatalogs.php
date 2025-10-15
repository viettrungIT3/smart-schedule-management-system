<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreCatalogs extends Migration
{
    public function up()
    {
        // classes
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'name' => ['type' => 'VARCHAR','constraint' => 50],
            'homeroom_teacher_id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'null' => true],
            'student_count' => ['type' => 'INT','constraint' => 11,'default' => 0],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('homeroom_teacher_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('classes');

        // subjects
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'name' => ['type' => 'VARCHAR','constraint' => 100],
            'description' => ['type' => 'TEXT','null' => true],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subjects');

        // rooms
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'name' => ['type' => 'VARCHAR','constraint' => 50],
            'function' => ['type' => 'VARCHAR','constraint' => 100,'null' => true],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rooms');

        // timeslots
        $this->forge->addField([
            'id' => ['type' => 'INT','constraint' => 11,'unsigned' => true,'auto_increment' => true],
            'name' => ['type' => 'VARCHAR','constraint' => 50], // e.g., Tiết 1
            'start_time' => ['type' => 'TIME'],
            'end_time' => ['type' => 'TIME'],
            'created_at' => ['type' => 'DATETIME','null' => true],
            'updated_at' => ['type' => 'DATETIME','null' => true],
            'deleted_at' => ['type' => 'DATETIME','null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('timeslots');
    }

    public function down()
    {
        $this->forge->dropTable('timeslots', true);
        $this->forge->dropTable('rooms', true);
        $this->forge->dropTable('subjects', true);
        $this->forge->dropTable('classes', true);
    }
}


