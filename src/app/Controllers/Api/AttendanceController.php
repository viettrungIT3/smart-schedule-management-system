<?php
namespace App\Controllers\Api;

use App\Models\AttendanceModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\BaseController;

class AttendanceController extends BaseController
{
    public function listBySchedule(int $scheduleId): ResponseInterface
    {
        $model = new AttendanceModel();
        return $this->response->setJSON(
            $model->where('schedule_id', $scheduleId)->findAll()
        );
    }

    public function markForSchedule(int $scheduleId): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? [];
        if (!is_array($payload)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid payload']);
        }

        $model = new AttendanceModel();
        $results = [];
        foreach ($payload as $row) {
            if (!isset($row['student_id'], $row['status'])) continue;
            $data = [
                'schedule_id' => $scheduleId,
                'student_id'  => (int)$row['student_id'],
                'status'      => (string)$row['status'],
                'note'        => $row['note'] ?? null,
            ];
            // upsert by unique (schedule_id, student_id)
            $existing = $model->where('schedule_id', $scheduleId)->where('student_id', $data['student_id'])->first();
            if ($existing) {
                $model->update($existing['id'], $data);
                $results[] = ['student_id' => $data['student_id'], 'updated' => true];
            } else {
                $model->insert($data);
                $results[] = ['student_id' => $data['student_id'], 'created' => true];
            }
        }
        return $this->response->setJSON(['done' => count($results), 'results' => $results]);
    }
}


