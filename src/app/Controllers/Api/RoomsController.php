<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\RoomModel;

class RoomsController extends BaseController
{
    protected $roomModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
    }

    public function index()
    {
        $rooms = $this->roomModel->getActiveRooms();

        return $this->respond([
            'success' => true,
            'data' => $rooms
        ]);
    }
}
