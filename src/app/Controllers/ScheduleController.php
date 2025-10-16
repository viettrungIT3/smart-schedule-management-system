<?php
namespace App\Controllers;

use App\Models\ScheduleModel;
use App\Services\SchedulerService;
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

    public function generate(): ResponseInterface
    {
        $svc = new SchedulerService();
        $rows = $svc->generateWeeklySchedules();
        return $this->response->setJSON(['created' => count($rows)]);
    }

    public function apply(): ResponseInterface
    {
        $svc = new SchedulerService();
        $n = $svc->applyGenerated();
        return $this->response->setJSON(['applied' => $n]);
    }
}


