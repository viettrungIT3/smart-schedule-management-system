<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'code',
        'description',
        'credits',
        'status',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'code' => 'required|min_length[2]|max_length[20]|is_unique[subjects.code,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
        'credits' => 'required|integer|greater_than[0]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Subject name is required',
            'min_length' => 'Subject name must be at least 2 characters',
            'max_length' => 'Subject name cannot exceed 100 characters'
        ],
        'code' => [
            'required' => 'Subject code is required',
            'min_length' => 'Subject code must be at least 2 characters',
            'max_length' => 'Subject code cannot exceed 20 characters',
            'is_unique' => 'Subject code already exists'
        ],
        'credits' => [
            'required' => 'Credits is required',
            'integer' => 'Credits must be a number',
            'greater_than' => 'Credits must be greater than 0'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be either active or inactive'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getActiveSubjects()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getSubjectByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    public function searchSubjects($keyword)
    {
        return $this->groupStart()
            ->like('name', $keyword)
            ->orLike('code', $keyword)
            ->orLike('description', $keyword)
            ->groupEnd()
            ->findAll();
    }
}
