<?php

namespace App\Controllers;

use App\Controller;
use App\Exceptions\NotFoundException;
use App\Models\User;

class UsersController extends Controller
{
    /** Presentation config for the register form. */
    private const REGISTER_FORM = [
        'heading'     => 'Register a user',
        'action'      => '/register',
        'submitLabel' => 'Register',
        'method'      => 'POST',     // fetch() verb the form submits with
    ];

    /** Presentation config for the edit form. */
    private const EDIT_FORM = [
        'heading'     => 'Edit user',
        'action'      => '/update',
        'submitLabel' => 'Save changes',
        'method'      => 'PUT',      // fetch() verb the form submits with
    ];

    public function get(): void
    {
        // $_GET values are always strings (or absent); normalise to the
        // types User::select() expects and treat empty input as "no filter".
        $name   = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null;
        $ageMin = isset($_GET['age_min']) && $_GET['age_min'] !== '' ? (int) $_GET['age_min'] : null;
        $ageMax = isset($_GET['age_max']) && $_GET['age_max'] !== '' ? (int) $_GET['age_max'] : null;

        $users = User::select($name, $ageMin, $ageMax);

        $this->view('users', ['title' => 'Users', 'users' => $users]);
    }

    /**
     * GET /register — show the empty registration form.
     */
    public function register(): void
    {
        $this->view('user_form', [
            ...self::REGISTER_FORM,
            'title'  => self::REGISTER_FORM['heading'],
            'fields' => User::fields(),
            'old'    => [],
        ]);
    }

    /**
     * POST /register — validate input and create the user. Returns JSON.
     */
    public function store(): void
    {
        $input  = $this->collectInput($this->jsonInput());
        $errors = User::validate($input);

        if ($errors !== []) {
            $this->json(['success' => false, 'errors' => $errors], 422);
            return;
        }

        User::create($input['username'], $input['first_name'], $input['last_name'], (int) $input['age']);

        $this->json(['success' => true]);
    }

    /**
     * GET /update?username=X — show the edit form pre-filled for one user.
     */
    public function edit(): void
    {
        $user = User::findByUsername($_GET['username'] ?? '');

        if ($user === null) {
            throw new NotFoundException('User not found.');
        }

        $this->view('user_form', [
            ...self::EDIT_FORM,
            'title'  => self::EDIT_FORM['heading'],
            'fields' => $this->editFields(),
            'old'    => $this->toInput($user),
        ]);
    }

    /**
     * PUT /update — validate and persist changes to an existing user. Returns JSON.
     */
    public function update(): void
    {
        $input    = $this->collectInput($this->jsonInput());
        $username = $input['username'];

        if (User::findByUsername($username) === null) {
            $this->json(['success' => false, 'errors' => ['username' => 'User not found.']], 404);
            return;
        }

        // Exclude this row from the uniqueness check (its own username is fine).
        $errors = User::validate($input, ignoreUsername: $username);

        if ($errors !== []) {
            $this->json(['success' => false, 'errors' => $errors], 422);
            return;
        }

        User::update($username, $input['first_name'], $input['last_name'], (int) $input['age']);

        $this->json(['success' => true]);
    }

    /**
     * DELETE /delete?username=X — remove a user. Returns JSON.
     */
    public function delete(): void
    {
        $username = trim($_GET['username'] ?? '');

        if ($username !== '') {
            // Idempotent: deleting a non-existent user is a harmless no-op.
            User::delete($username);
        }

        $this->json(['success' => true]);
    }

    /**
     * Pick the known user fields out of a raw input array and trim them.
     *
     * @param array<string, mixed> $source
     * @return array<string, string>
     */
    private function collectInput(array $source): array
    {
        $input = [];
        foreach (User::fieldKeys() as $field) {
            $input[$field] = trim((string) ($source[$field] ?? ''));
        }

        return $input;
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
     * Fields are derived from the model, so new fields flow through automatically.
     *
     * @return array<string, string>
     */
    private function toInput(User $user): array
    {
        $input = [];
        foreach (User::fieldKeys() as $field) {
            $input[$field] = (string) $user->$field;
        }

        return $input;
    }
}
