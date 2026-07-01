<?php

namespace App\Controllers;

use App\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function get(): void
    {
        // $_GET values are always strings (or absent); normalise to the
        // types User::select() expects and treat empty input as "no filter".
        $name = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null;
        $age  = isset($_GET['age']) && $_GET['age'] !== '' ? (int) $_GET['age'] : null;

        $users = User::select($name, $age);

        $this->view('users', ['users' => $users]);
    }
}
