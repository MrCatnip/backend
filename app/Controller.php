<?php

namespace App;

class Controller
{
    /**
     * Render a view file from app/Views/.
     *
     * @param array<string, mixed> $data  variables to expose to the view
     */
    protected function view(string $name, array $data = []): void
    {
        extract($data);                       // turns ['title' => 'x'] into $title
        require __DIR__ . "/Views/$name.php"; // load + run the template
    }

    /**
     * Send data as a JSON response (for the API).
     *
     * @param array<string, mixed>|list<mixed> $data
     */
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
