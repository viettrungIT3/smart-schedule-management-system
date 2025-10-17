<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        // Show landing page for non-logged in users
        return view('welcome_message');
    }
}
