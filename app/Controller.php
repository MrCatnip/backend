<?php

namespace App;

class Controller
{
    /**
     * Render a view file from app/Views/ inside the shared layout.
     *
     * @param array<string, mixed> $data  variables to expose to the view
     */
    protected function view(string $name, array $data = []): void
    {
        extract($data);                       // turns ['title' => 'x'] into $title

        // Capture the view's output, then hand it to the layout as $content.
        ob_start();
        require __DIR__ . "/Views/$name.php";
        $content = ob_get_clean();

        $title = $data['title'] ?? 'Mini CMS';
        require __DIR__ . '/Views/layout.php';
    }

    /**
     * Send data as a JSON response (for the API).
     *
     * @param array<string, mixed>|list<mixed> $data
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Decode a JSON request body. PHP only populates $_POST for POST form
     * submissions, so PUT/PATCH/DELETE payloads must be read from the raw body.
     *
     * @return array<string, mixed>
     */
    protected function jsonInput(): array
    {
        $data = json_decode((string) file_get_contents('php://input'), true);

        return is_array($data) ? $data : [];
    }

    /**
     * Redirect to another path and stop execution.
     * Uses 303 so the browser follows up with a GET (Post/Redirect/Get).
     */
    protected function redirect(string $path): never
    {
        header("Location: $path", true, 303);
        exit;
    }
}
