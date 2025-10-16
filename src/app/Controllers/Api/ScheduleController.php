<?php
namespace App\Controllers;

use App\Models\ScheduleModel;
use CodeIgniter\HTTP\ResponseInterface;

class ScheduleController extends BaseController
{
    public function byClass(int $classId): ResponseInterface
    {
        $model = new ScheduleModel();
        return $this->response->setJSON($model->listByClass($classId));
    }

    public function byTeacher(int $teacherId): ResponseInterface
    {
        $model = new ScheduleModel();
        return $this->response->setJSON($model->listByTeacher($teacherId));
    }

    public function search(): ResponseInterface
    {
        $model = new ScheduleModel();
        $classId = $this->request->getGet('class_id');
        $teacherId = $this->request->getGet('teacher_id');
        $weekday = $this->request->getGet('weekday');
        $page = max(1, (int)$this->request->getGet('page'));
        $perPage = min(50, max(1, (int)$this->request->getGet('per_page') ?: 10));

        $builder = $model->select('schedules.*, classes.name AS class_name, subjects.name AS subject_name, rooms.name AS room_name, timeslots.name AS timeslot_name')
            ->join('classes', 'classes.id = schedules.class_id')
            ->join('subjects', 'subjects.id = schedules.subject_id')
            ->join('rooms', 'rooms.id = schedules.room_id')
            ->join('timeslots', 'timeslots.id = schedules.timeslot_id');

        if ($classId) $builder->where('schedules.class_id', (int)$classId);
        if ($teacherId) $builder->where('schedules.teacher_id', (int)$teacherId);
        if ($weekday) $builder->where('schedules.weekday', (int)$weekday);

        $results = $builder->orderBy('weekday ASC, timeslot_id ASC')
            ->paginate($perPage, 'default', $page);

        return $this->response->setJSON([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $model->pager->getTotal(),
            'data' => $results,
        ]);
    }
}


