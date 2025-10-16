<?php
namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\BaseController;

class HealthController extends BaseController
{
    public function index(): ResponseInterface
    {
        return $this->response->setJSON(['status' => 'ok']);
    }
}


