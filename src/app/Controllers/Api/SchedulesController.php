<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ScheduleModel;
use App\Models\SubjectModel;
use App\Models\RoomModel;
use App\Models\UserModel;

class SchedulesController extends BaseController
{
    protected $scheduleModel;
    protected $subjectModel;
    protected $roomModel;
    protected $userModel;

    public function __construct()
    {
        $this->scheduleModel = new ScheduleModel();
        $this->subjectModel = new SubjectModel();
        $this->roomModel = new RoomModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start') ?? 0;
        $length = $this->request->getGet('length') ?? 10;
        $search = $this->request->getGet('search')['value'] ?? '';
        $orderColumn = $this->request->getGet('order')[0]['column'] ?? 0;
        $orderDir = $this->request->getGet('order')[0]['dir'] ?? 'asc';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $status = $this->request->getGet('status');

        // Get column names for ordering
        $columns = ['id', 'subject_name', 'teacher_name', 'room_name', 'schedule_date', 'start_time', 'duration', 'status'];
        $orderBy = $columns[$orderColumn] ?? 'schedule_date';

        // Build query
        $builder = $this->scheduleModel->select('
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
            ->join('rooms', 'rooms.id = schedules.room_id');

        // Apply filters
        if ($startDate) {
            $builder->where('schedules.schedule_date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('schedules.schedule_date <=', $endDate);
        }
        if ($status) {
            $builder->where('schedules.status', $status);
        }

        // Apply search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('subjects.name', $search)
                ->orLike('users.first_name', $search)
                ->orLike('users.last_name', $search)
                ->orLike('rooms.name', $search)
                ->orLike('schedules.description', $search)
                ->groupEnd();
        }

        // Get total count
        $totalRecords = $this->scheduleModel->countAllResults(false);

        // Apply ordering and pagination
        $schedules = $builder->orderBy($orderBy, $orderDir)
            ->limit($length, $start)
            ->findAll();

        // Format data for DataTables
        $data = [];
        foreach ($schedules as $schedule) {
            $data[] = [
                'id' => $schedule['id'],
                'subject_name' => $schedule['subject_name'],
                'teacher_name' => $schedule['teacher_name'],
                'room_name' => $schedule['room_name'],
                'schedule_date' => $schedule['schedule_date'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
                'duration' => $schedule['duration'],
                'status' => $schedule['status']
            ];
        }

        return $this->respond([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function calendar()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        $events = $this->scheduleModel->getCalendarEvents($start, $end);

        $calendarEvents = [];
        foreach ($events as $event) {
            $calendarEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'] . ' - ' . $event['teacher_name'],
                'start' => $event['schedule_date'] . 'T' . $event['start_time'],
                'end' => $event['schedule_date'] . 'T' . $event['end_time'],
                'backgroundColor' => $this->getEventColor($event['status']),
                'borderColor' => $this->getEventColor($event['status']),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'status' => $event['status'],
                    'description' => $event['description'],
                    'teacher_name' => $event['teacher_name'],
                    'room_name' => $event['room_name'],
                    'subject_code' => $event['subject_code']
                ]
            ];
        }

        return $this->respond($calendarEvents);
    }

    public function show($id)
    {
        $schedule = $this->scheduleModel->getScheduleWithDetails($id);

        if (!$schedule) {
            return $this->respond([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        return $this->respond([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function store()
    {
        $rules = [
            'subject_id' => 'required|integer',
            'teacher_id' => 'required|integer',
            'room_id' => 'required|integer',
            'schedule_date' => 'required|valid_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $scheduleData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'teacher_id' => $this->request->getPost('teacher_id'),
            'room_id' => $this->request->getPost('room_id'),
            'schedule_date' => $this->request->getPost('schedule_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'description' => $this->request->getPost('description'),
            'status' => 'active'
        ];

        // Calculate duration
        $start = strtotime($scheduleData['start_time']);
        $end = strtotime($scheduleData['end_time']);
        $scheduleData['duration'] = ($end - $start) / 60; // in minutes

        // Check room availability
        if (
            !$this->scheduleModel->checkRoomAvailability(
                $scheduleData['room_id'],
                $scheduleData['schedule_date'],
                $scheduleData['start_time'],
                $scheduleData['end_time']
            )
        ) {
            return $this->respond([
                'success' => false,
                'message' => 'Room is not available at the selected time'
            ], 400);
        }

        $scheduleId = $this->scheduleModel->insert($scheduleData);

        if ($scheduleId) {
            return $this->respond([
                'success' => true,
                'message' => 'Schedule created successfully',
                'data' => ['id' => $scheduleId]
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to create schedule'
        ], 500);
    }

    public function update($id)
    {
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule) {
            return $this->respond([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        $rules = [
            'subject_id' => 'required|integer',
            'teacher_id' => 'required|integer',
            'room_id' => 'required|integer',
            'schedule_date' => 'required|valid_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required|in_list[active,completed,cancelled]',
            'description' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $scheduleData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'teacher_id' => $this->request->getPost('teacher_id'),
            'room_id' => $this->request->getPost('room_id'),
            'schedule_date' => $this->request->getPost('schedule_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description')
        ];

        // Calculate duration
        $start = strtotime($scheduleData['start_time']);
        $end = strtotime($scheduleData['end_time']);
        $scheduleData['duration'] = ($end - $start) / 60; // in minutes

        // Check room availability (exclude current schedule)
        if (
            !$this->scheduleModel->checkRoomAvailability(
                $scheduleData['room_id'],
                $scheduleData['schedule_date'],
                $scheduleData['start_time'],
                $scheduleData['end_time'],
                $id
            )
        ) {
            return $this->respond([
                'success' => false,
                'message' => 'Room is not available at the selected time'
            ], 400);
        }

        if ($this->scheduleModel->update($id, $scheduleData)) {
            return $this->respond([
                'success' => true,
                'message' => 'Schedule updated successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to update schedule'
        ], 500);
    }

    public function delete($id)
    {
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule) {
            return $this->respond([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        if ($this->scheduleModel->delete($id)) {
            return $this->respond([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to delete schedule'
        ], 500);
    }

    private function getEventColor($status)
    {
        switch ($status) {
            case 'active':
                return '#28a745';
            case 'completed':
                return '#007bff';
            case 'cancelled':
                return '#dc3545';
            default:
                return '#6c757d';
        }
    }
}
