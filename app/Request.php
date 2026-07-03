<?php

namespace App;

class Request
{
    /**
     * Whether the client prefers a JSON response.
     *
     * Considers both the Accept header (what the client says it wants back)
     * and the request's Content-Type (a client that sent us JSON almost
     * certainly wants JSON back, even if it didn't set Accept). Shared by the
     * controllers and the error handler so negotiation stays consistent
     * across normal responses and error responses.
     */
    public static function wantsJson(): bool
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
            || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');
    }
}
