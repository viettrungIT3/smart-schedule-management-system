<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SubjectModel;

class SubjectsController extends BaseController
{
    protected $subjectModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        $subjects = $this->subjectModel->getActiveSubjects();

        return $this->respond([
            'success' => true,
            'data' => $subjects
        ]);
    }
}
