<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'subject_id',
        'teacher_id',
        'room_id',
        'schedule_date',
        'start_time',
        'end_time',
        'duration',
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
        'subject_id' => 'required|integer',
        'teacher_id' => 'required|integer',
        'room_id' => 'required|integer',
        'schedule_date' => 'required|valid_date',
        'start_time' => 'required',
        'end_time' => 'required',
        'duration' => 'required|integer|greater_than[0]',
        'description' => 'permit_empty|max_length[500]',
        'status' => 'required|in_list[active,completed,cancelled]'
    ];

    protected $validationMessages = [
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Subject must be a valid selection'
        ],
        'teacher_id' => [
            'required' => 'Teacher is required',
            'integer' => 'Teacher must be a valid selection'
        ],
        'room_id' => [
            'required' => 'Room is required',
            'integer' => 'Room must be a valid selection'
        ],
        'schedule_date' => [
            'required' => 'Schedule date is required',
            'valid_date' => 'Schedule date must be a valid date'
        ],
        'start_time' => [
            'required' => 'Start time is required'
        ],
        'end_time' => [
            'required' => 'End time is required'
        ],
        'duration' => [
            'required' => 'Duration is required',
            'integer' => 'Duration must be a number',
            'greater_than' => 'Duration must be greater than 0'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be active, completed, or cancelled'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['calculateDuration'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['calculateDuration'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function calculateDuration(array $data)
    {
        if (isset($data['data']['start_time']) && isset($data['data']['end_time'])) {
            $start = strtotime($data['data']['start_time']);
            $end = strtotime($data['data']['end_time']);
            $data['data']['duration'] = ($end - $start) / 60; // in minutes
        }
        return $data;
    }

    public function getSchedulesWithDetails()
    {
        return $this->select('
            schedules.*,
            subjects.name as subject_name,
            subjects.code as subject_code,
            CONCAT(users.first_name, " ", users.last_name) as teacher_name,
            rooms.name as room_name,
            rooms.building,
            rooms.floor
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('users', 'users.id = schedules.teacher_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->orderBy('schedules.schedule_date', 'DESC')
            ->orderBy('schedules.start_time', 'ASC')
            ->findAll();
    }

    public function getScheduleWithDetails($id)
    {
        return $this->select('
            schedules.*,
            subjects.name as subject_name,
            subjects.code as subject_code,
            subjects.description as subject_description,
            CONCAT(users.first_name, " ", users.last_name) as teacher_name,
            users.email as teacher_email,
            rooms.name as room_name,
            rooms.building,
            rooms.floor,
            rooms.capacity,
            rooms.equipment
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('users', 'users.id = schedules.teacher_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->where('schedules.id', $id)
            ->first();
    }

    public function getSchedulesByDate($date)
    {
        return $this->select('
            schedules.*,
            subjects.name as subject_name,
            subjects.code as subject_code,
            CONCAT(users.first_name, " ", users.last_name) as teacher_name,
            rooms.name as room_name,
            rooms.building,
            rooms.floor
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('users', 'users.id = schedules.teacher_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->where('schedules.schedule_date', $date)
            ->orderBy('schedules.start_time', 'ASC')
            ->findAll();
    }

    public function getSchedulesByTeacher($teacherId, $startDate = null, $endDate = null)
    {
        $builder = $this->select('
            schedules.*,
            subjects.name as subject_name,
            subjects.code as subject_code,
            rooms.name as room_name,
            rooms.building,
            rooms.floor
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->where('schedules.teacher_id', $teacherId);

        if ($startDate) {
            $builder->where('schedules.schedule_date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('schedules.schedule_date <=', $endDate);
        }

        return $builder->orderBy('schedules.schedule_date', 'ASC')
            ->orderBy('schedules.start_time', 'ASC')
            ->findAll();
    }

    public function getSchedulesByRoom($roomId, $startDate = null, $endDate = null)
    {
        $builder = $this->select('
            schedules.*,
            subjects.name as subject_name,
            subjects.code as subject_code,
            CONCAT(users.first_name, " ", users.last_name) as teacher_name
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('users', 'users.id = schedules.teacher_id')
            ->where('schedules.room_id', $roomId);

        if ($startDate) {
            $builder->where('schedules.schedule_date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('schedules.schedule_date <=', $endDate);
        }

        return $builder->orderBy('schedules.schedule_date', 'ASC')
            ->orderBy('schedules.start_time', 'ASC')
            ->findAll();
    }

    public function getCalendarEvents($startDate = null, $endDate = null)
    {
        $builder = $this->select('
            schedules.id,
            schedules.schedule_date,
            schedules.start_time,
            schedules.end_time,
            schedules.status,
            schedules.description,
            subjects.name as title,
            subjects.code as subject_code,
            CONCAT(users.first_name, " ", users.last_name) as teacher_name,
            rooms.name as room_name
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('users', 'users.id = schedules.teacher_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->where('schedules.status', 'active');

        if ($startDate) {
            $builder->where('schedules.schedule_date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('schedules.schedule_date <=', $endDate);
        }

        return $builder->orderBy('schedules.schedule_date', 'ASC')
            ->orderBy('schedules.start_time', 'ASC')
            ->findAll();
    }

    public function checkRoomAvailability($roomId, $date, $startTime, $endTime, $excludeId = null)
    {
        $builder = $this->where('room_id', $roomId)
            ->where('schedule_date', $date)
            ->where('status', 'active')
            ->groupStart()
            ->where('start_time <=', $startTime)
            ->where('end_time >', $startTime)
            ->orGroupStart()
            ->where('start_time <', $endTime)
            ->where('end_time >=', $endTime)
            ->orGroupStart()
            ->where('start_time >=', $startTime)
            ->where('end_time <=', $endTime)
            ->groupEnd()
            ->groupEnd();

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() === 0;
    }

    public function getUpcomingSchedules($days = 7)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));

        return $this->select('
            schedules.*,
            subjects.name as subject_name,
            subjects.code as subject_code,
            CONCAT(users.first_name, " ", users.last_name) as teacher_name,
            rooms.name as room_name,
            rooms.building,
            rooms.floor
        ')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('users', 'users.id = schedules.teacher_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->where('schedules.schedule_date >=', $startDate)
            ->where('schedules.schedule_date <=', $endDate)
            ->where('schedules.status', 'active')
            ->orderBy('schedules.schedule_date', 'ASC')
            ->orderBy('schedules.start_time', 'ASC')
            ->findAll();
    }
}