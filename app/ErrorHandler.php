<?php

namespace App;

use ErrorException;
use Throwable;

class ErrorHandler
{
    public function __construct(private bool $debug = false)
    {
    }

    /**
     * Route PHP errors, uncaught exceptions and fatal errors through one place.
     */
    public function register(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '0'); // we render errors ourselves

        set_error_handler($this->handleError(...));
        set_exception_handler($this->handleException(...));
        register_shutdown_function($this->handleShutdown(...));
    }

    /**
     * Promote PHP warnings/notices to exceptions so they can't pass silently.
     */
    public function handleError(int $level, string $message, string $file, int $line): bool
    {
        // Respect @-suppression and the current error_reporting level.
        if ((error_reporting() & $level) === 0) {
            return false;
        }

        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Render an uncaught throwable as a 500 response (HTML or JSON).
     */
    public function handleException(Throwable $e): void
    {
        error_log((string) $e);

        if (headers_sent() === false) {
            http_response_code(500);
        }

        $this->wantsJson() ? $this->renderJson($e) : $this->renderHtml($e);
    }

    /**
     * Catch fatal errors (e.g. out of memory) that bypass set_error_handler.
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            $this->handleException(
                new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
            );
        }
    }

    private function wantsJson(): bool
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
            || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');
    }

    private function renderJson(Throwable $e): void
    {
        if (headers_sent() === false) {
            header('Content-Type: application/json');
        }

        echo json_encode([
            'success' => false,
            'message' => $this->debug ? $e->getMessage() : 'Something went wrong.',
        ]);
    }

    private function renderHtml(Throwable $e): void
    {
        if (headers_sent() === false) {
            header('Content-Type: text/html; charset=utf-8');
        }

        if ($this->debug) {
            echo '<h1>500 &mdash; ' . htmlspecialchars($e->getMessage()) . '</h1>';
            echo '<pre>' . htmlspecialchars((string) $e) . '</pre>';
            return;
        }

        echo '<h1>500 &mdash; Something went wrong</h1>';
        echo '<p>Please try again later.</p>';
    }
}
