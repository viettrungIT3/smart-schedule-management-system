<?php
namespace App\Models;

use CodeIgniter\Model;

class TimeslotModel extends Model
{
    protected $table = 'timeslots';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'name', 'start_time', 'end_time',
        'created_at', 'updated_at', 'deleted_at'
    ];
}


