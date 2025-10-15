<?php
namespace App\Models;

use CodeIgniter\Model;

class TeachingAssignmentModel extends Model
{
    protected $table = 'teaching_assignments';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'teacher_id', 'class_id', 'subject_id', 'periods_per_week',
        'created_at', 'updated_at', 'deleted_at'
    ];
}


