<?php
namespace App\Controllers\Api;

use App\Models\TeachingAssignmentModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\BaseController;

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

    public function update(int $id): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        $model = new TeachingAssignmentModel();
        if (!$model->update($id, $data)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $model->errors()]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function delete(int $id): ResponseInterface
    {
        $model = new TeachingAssignmentModel();
        if (!$model->delete($id)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Assignment not found']);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function list(): ResponseInterface
    {
        $model = new TeachingAssignmentModel();
        $page = max(1, (int)$this->request->getGet('page'));
        $perPage = min(50, max(1, (int)$this->request->getGet('per_page') ?: 10));

        $builder = $model->select('teaching_assignments.*, users.full_name as teacher_name, subjects.name as subject_name, classes.name as class_name')
            ->join('users', 'users.id = teaching_assignments.teacher_id')
            ->join('subjects', 'subjects.id = teaching_assignments.subject_id')
            ->join('classes', 'classes.id = teaching_assignments.class_id');

        $results = $builder->orderBy('class_id ASC, subject_id ASC')
            ->paginate($perPage, 'default', $page);

        return $this->response->setJSON([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $model->pager->getTotal(),
            'data' => $results,
        ]);
    }
}


