<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'class_id',
        'subject_id',
        'teacher_id',
        'room_id',
        'timeslot_id',
        'weekday',
        'week',
        'is_applied',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function listByClass(int $classId): array
    {
        return $this->select('schedules.*, subjects.name AS subject_name, rooms.name AS room_name, timeslots.name AS timeslot_name')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->join('timeslots', 'timeslots.id = schedules.timeslot_id')
            ->where('schedules.class_id', $classId)
            ->orderBy('weekday ASC, timeslot_id ASC')
            ->findAll();
    }

    public function listByTeacher(int $teacherId): array
    {
        return $this->select('schedules.*, classes.name AS class_name, subjects.name AS subject_name, rooms.name AS room_name, timeslots.name AS timeslot_name')
            ->join('classes', 'classes.id = schedules.class_id')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->join('timeslots', 'timeslots.id = schedules.timeslot_id')
            ->where('schedules.teacher_id', $teacherId)
            ->orderBy('weekday ASC, timeslot_id ASC')
            ->findAll();
    }
}
