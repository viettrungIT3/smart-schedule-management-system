<?php
namespace App\Controllers\Api;

use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\BaseController;

class NotificationController extends BaseController
{
    public function listByUser(int $userId): ResponseInterface
    {
        $model = new NotificationModel();
        return $this->response->setJSON(
            $model->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll()
        );
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        if (!isset($data['user_id'], $data['title'], $data['message'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'user_id, title, message required']);
        }
        $model = new NotificationModel();
        if (!$model->insert([
            'user_id' => (int)$data['user_id'],
            'title' => (string)$data['title'],
            'message' => (string)$data['message'],
            'is_read' => 0,
        ])) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $model->errors()]);
        }
        return $this->response->setJSON(['id' => $model->getInsertID()]);
    }
}


