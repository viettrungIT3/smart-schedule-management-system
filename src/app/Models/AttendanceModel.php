<?php
namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'schedule_id', 'student_id', 'status', 'note',
        'created_at', 'updated_at', 'deleted_at'
    ];
}


