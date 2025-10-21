<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'code',
        'building',
        'floor',
        'capacity',
        'equipment',
        'description',
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
        'code' => 'required|min_length[2]|max_length[20]|is_unique[rooms.code,id,{id}]',
        'building' => 'required|min_length[2]|max_length[50]',
        'floor' => 'required|integer',
        'capacity' => 'required|integer|greater_than[0]',
        'equipment' => 'permit_empty|max_length[500]',
        'description' => 'permit_empty|max_length[500]',
        'status' => 'required|in_list[active,inactive,maintenance]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Room name is required',
            'min_length' => 'Room name must be at least 2 characters',
            'max_length' => 'Room name cannot exceed 100 characters'
        ],
        'code' => [
            'required' => 'Room code is required',
            'min_length' => 'Room code must be at least 2 characters',
            'max_length' => 'Room code cannot exceed 20 characters',
            'is_unique' => 'Room code already exists'
        ],
        'building' => [
            'required' => 'Building is required',
            'min_length' => 'Building must be at least 2 characters',
            'max_length' => 'Building cannot exceed 50 characters'
        ],
        'floor' => [
            'required' => 'Floor is required',
            'integer' => 'Floor must be a number'
        ],
        'capacity' => [
            'required' => 'Capacity is required',
            'integer' => 'Capacity must be a number',
            'greater_than' => 'Capacity must be greater than 0'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be active, inactive, or maintenance'
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

    public function getActiveRooms()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getRoomByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    public function getRoomsByBuilding($building)
    {
        return $this->where('building', $building)->findAll();
    }

    public function getAvailableRooms($date, $startTime, $endTime)
    {
        // This would check for room availability
        // For now, return all active rooms
        return $this->where('status', 'active')->findAll();
    }

    public function searchRooms($keyword)
    {
        return $this->groupStart()
            ->like('name', $keyword)
            ->orLike('code', $keyword)
            ->orLike('building', $keyword)
            ->orLike('description', $keyword)
            ->groupEnd()
            ->findAll();
    }
}
