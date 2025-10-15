<?php
namespace App\Controllers;

use App\Models\TeachingAssignmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class TeachingAssignmentController extends BaseController
{
    public function listByClass(int $classId): ResponseInterface
    {
        $model = new TeachingAssignmentModel();
        $rows = $model->select('teaching_assignments.*, users.full_name as teacher_name, subjects.name as subject_name')
            ->join('users', 'users.id = teaching_assignments.teacher_id')
            ->join('subjects', 'subjects.id = teaching_assignments.subject_id')
            ->where('teaching_assignments.class_id', $classId)
            ->findAll();
        return $this->response->setJSON($rows);
    }

    public function listByTeacher(int $teacherId): ResponseInterface
    {
        $model = new TeachingAssignmentModel();
        $rows = $model->select('teaching_assignments.*, classes.name as class_name, subjects.name as subject_name')
            ->join('classes', 'classes.id = teaching_assignments.class_id')
            ->join('subjects', 'subjects.id = teaching_assignments.subject_id')
            ->where('teaching_assignments.teacher_id', $teacherId)
            ->findAll();
        return $this->response->setJSON($rows);
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        $model = new TeachingAssignmentModel();
        if (!$model->insert($data)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $model->errors()]);
        }
        return $this->response->setJSON(['id' => $model->getInsertID()]);
    }
}


