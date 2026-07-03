<?php

namespace App;

class Response
{
    /**
     * Emit an array as a JSON response.
     *
     * Owns the JSON response headers (status + Content-Type) so they're
     * defined in exactly one place. The writes are guarded with headers_sent()
     * so this is safe to call even from the error handler, where output may
     * already have started before the failure.
     *
     * @param array<string, mixed>|list<mixed> $data
     */
    public static function json(array $data, int $status = 200): void
    {
        if (headers_sent() === false) {
            http_response_code($status);
            header('Content-Type: application/json');
        }

        echo json_encode($data);
    }
}
