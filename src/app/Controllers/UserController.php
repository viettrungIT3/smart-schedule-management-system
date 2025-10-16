<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    public function index(): ResponseInterface
    {
        $model = new UserModel();
        return $this->response->setJSON($model->findAll());
    }

    public function show(int $id): ResponseInterface
    {
        $model = new UserModel();
        $user = $model->find($id);
        if (!$user) return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        return $this->response->setJSON($user);
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        $model = new UserModel();
        if (!empty($data['password'])) {
            $data['password_hash'] = password_hash((string)$data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        if (!$model->insert($data)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $model->errors()]);
        }
        return $this->response->setJSON(['id' => $model->getInsertID()]);
    }

    public function updateUser(int $id): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];
        $model = new UserModel();
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash((string)$data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        if (!$model->update($id, $data)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $model->errors()]);
        }
        return $this->response->setJSON(['updated' => true]);
    }

    public function deleteUser(int $id): ResponseInterface
    {
        $model = new UserModel();
        $model->delete($id);
        return $this->response->setJSON(['deleted' => true]);
    }
}


