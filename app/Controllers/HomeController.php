<?php

namespace App\Controllers;

use App\Controller;

class HomeController extends Controller
{
    public function get(): void
    {
        $this->view('home');
    }
}
