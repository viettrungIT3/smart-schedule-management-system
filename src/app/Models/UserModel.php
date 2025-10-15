<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'role',
        'full_name',
        'email',
        'password_hash',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
