<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * SwaggerController
 * 
 * Controller for serving Swagger UI documentation
 */
class SwaggerController extends Controller
{
    /**
     * Display Swagger UI interface
     * 
     * @return string
     */
    public function index()
    {
        return view('swagger_view');
    }
}
