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
        $name   = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null;
        $ageMin = isset($_GET['age_min']) && $_GET['age_min'] !== '' ? (int) $_GET['age_min'] : null;
        $ageMax = isset($_GET['age_max']) && $_GET['age_max'] !== '' ? (int) $_GET['age_max'] : null;

        $users = User::select($name, $ageMin, $ageMax);

        $this->view('users', ['users' => $users]);
    }
}
