<?php

namespace App\Controllers;

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
        $data = [
            'title' => 'Schedule Management - ScheduleFlow',
            'schedules' => $this->scheduleModel->getSchedulesWithDetails()
        ];

        return view('schedules/index', $data);
    }

    public function calendar()
    {
        $data = [
            'title' => 'Schedule Calendar - ScheduleFlow'
        ];

        return view('schedules/calendar', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Schedule - ScheduleFlow',
            'subjects' => $this->subjectModel->findAll(),
            'rooms' => $this->roomModel->findAll(),
            'teachers' => $this->userModel->getUsersByRole('teacher')
        ];

        return view('schedules/create', $data);
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
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $scheduleData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'teacher_id' => $this->request->getPost('teacher_id'),
            'room_id' => $this->request->getPost('room_id'),
            'schedule_date' => $this->request->getPost('schedule_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'description' => $this->request->getPost('description'),
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Calculate duration
        $start = strtotime($scheduleData['start_time']);
        $end = strtotime($scheduleData['end_time']);
        $scheduleData['duration'] = ($end - $start) / 60; // in minutes

        $scheduleId = $this->scheduleModel->insert($scheduleData);

        if ($scheduleId) {
            return redirect()->to('/schedules')
                ->with('success', 'Schedule created successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create schedule');
    }

    public function show($id)
    {
        $schedule = $this->scheduleModel->getScheduleWithDetails($id);

        if (!$schedule) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Schedule not found');
        }

        $data = [
            'title' => 'Schedule Details - ScheduleFlow',
            'schedule' => $schedule
        ];

        return view('schedules/show', $data);
    }

    public function edit($id)
    {
        $schedule = $this->scheduleModel->getScheduleWithDetails($id);

        if (!$schedule) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Schedule not found');
        }

        $data = [
            'title' => 'Edit Schedule - ScheduleFlow',
            'schedule' => $schedule,
            'subjects' => $this->subjectModel->findAll(),
            'rooms' => $this->roomModel->findAll(),
            'teachers' => $this->userModel->getUsersByRole('teacher')
        ];

        return view('schedules/edit', $data);
    }

    public function update($id)
    {
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Schedule not found');
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
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $scheduleData = [
            'subject_id' => $this->request->getPost('subject_id'),
            'teacher_id' => $this->request->getPost('teacher_id'),
            'room_id' => $this->request->getPost('room_id'),
            'schedule_date' => $this->request->getPost('schedule_date'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Calculate duration
        $start = strtotime($scheduleData['start_time']);
        $end = strtotime($scheduleData['end_time']);
        $scheduleData['duration'] = ($end - $start) / 60; // in minutes

        if ($this->scheduleModel->update($id, $scheduleData)) {
            return redirect()->to('/schedules')
                ->with('success', 'Schedule updated successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update schedule');
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
}
