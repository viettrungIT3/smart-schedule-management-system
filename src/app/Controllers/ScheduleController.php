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
}


