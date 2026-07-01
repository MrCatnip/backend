<?php

namespace App\Controllers;

use App\Controller;
use App\Models\User;

class UsersController extends Controller
{
    /** Presentation config for the register form (heading/action/button). */
    private const REGISTER_FORM = [
        'heading'     => 'Register a user',
        'action'      => '/register',
        'submitLabel' => 'Register',
    ];

    /** Presentation config for the edit form. */
    private const EDIT_FORM = [
        'heading'     => 'Edit user',
        'action'      => '/update',
        'submitLabel' => 'Save changes',
    ];

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
        $this->view('user_form', [
            ...self::REGISTER_FORM,
            'fields' => User::fields(),
            'old'    => [],
            'errors' => [],
        ]);
    }

    /**
     * POST /register — validate input and create the user.
     */
    public function store(): void
    {
        $old    = $this->collectInput();
        $errors = User::validate($old);

        if ($errors !== []) {
            $this->view('user_form', [
                ...self::REGISTER_FORM,
                'fields' => User::fields(),
                'old'    => $old,
                'errors' => $errors,
            ]);
            return;
        }

        User::create($old['username'], $old['first_name'], $old['last_name'], (int) $old['age']);

        // Post/Redirect/Get: avoid resubmission on refresh.
        $this->redirect('/users');
    }

    /**
     * GET /update?username=X — show the edit form pre-filled for one user.
     */
    public function edit(): void
    {
        $user = User::findByUsername($_GET['username'] ?? '');

        if ($user === null) {
            $this->notFound();
            return;
        }

        $this->view('user_form', [
            ...self::EDIT_FORM,
            'fields' => $this->editFields(),
            'old'    => $this->toInput($user),
            'errors' => [],
        ]);
    }

    /**
     * POST /update — validate and persist changes to an existing user.
     */
    public function update(): void
    {
        $old      = $this->collectInput();
        $username = $old['username'];

        if (User::findByUsername($username) === null) {
            $this->notFound();
            return;
        }

        // Exclude this row from the uniqueness check (its own username is fine).
        $errors = User::validate($old, ignoreUsername: $username);

        if ($errors !== []) {
            $this->view('user_form', [
                ...self::EDIT_FORM,
                'fields' => $this->editFields(),
                'old'    => $old,
                'errors' => $errors,
            ]);
            return;
        }

        User::update($username, $old['first_name'], $old['last_name'], (int) $old['age']);

        $this->redirect('/users');
    }

    /**
     * POST /delete — remove a user, then return to the list.
     */
    public function delete(): void
    {
        $username = trim($_POST['username'] ?? '');

        if ($username !== '') {
            // Idempotent: deleting a non-existent user is a harmless no-op.
            User::delete($username);
        }

        $this->redirect('/users');
    }

    /**
     * Collect the known user fields from the POST request (HTTP concern).
     *
     * @return array<string, string>
     */
    private function collectInput(): array
    {
        $old = [];
        foreach (User::fieldKeys() as $field) {
            $old[$field] = trim($_POST[$field] ?? '');
        }

        return $old;
    }

    /**
     * Field config for editing: the username is the identity, so it's read-only.
     *
     * @return array<string, array<string, mixed>>
     */
    private function editFields(): array
    {
        $fields = User::fields();
        $fields['username']['readonly'] = true;

        return $fields;
    }

    /**
     * Flatten a User into the string-keyed shape the form expects.
     *
     * @return array<string, string>
     */
    private function toInput(User $user): array
    {
        return [
            'username'   => $user->username,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'age'        => (string) $user->age,
        ];
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo 'User not found.';
    }
}
