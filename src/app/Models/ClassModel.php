<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'name',
        'homeroom_teacher_id',
        'student_count',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
