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

    /**
     * GET /register — show the empty registration form.
     */
    public function register(): void
    {
        $this->view('register', ['errors' => [], 'old' => []]);
    }

    /**
     * POST /register — validate input and create the user.
     */
    public function store(): void
    {
        // Collect the known fields from the request (HTTP concern).
        $old = [];
        foreach (User::fieldKeys() as $field) {
            $old[$field] = trim($_POST[$field] ?? '');
        }

        // Ask the domain whether the input is valid.
        $errors = User::validate($old);

        if ($errors !== []) {
            // Re-render the form with errors and the values already entered.
            $this->view('register', ['errors' => $errors, 'old' => $old]);
            return;
        }

        User::create($old['username'], $old['first_name'], $old['last_name'], (int) $old['age']);

        // Post/Redirect/Get: avoid resubmission on refresh.
        $this->redirect('/users');
    }
}
