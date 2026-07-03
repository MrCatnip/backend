<?php

namespace App;

use App\Exceptions\NotFoundException;
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
     * Render an uncaught throwable: 404 for NotFoundException, else 500.
     * Negotiates HTML vs JSON based on the request.
     */
    public function handleException(Throwable $e): void
    {
        $isNotFound = $e instanceof NotFoundException;
        $status     = $isNotFound ? 404 : 500;

        // 404s are an expected outcome, not a server fault — no need to log.
        if ($isNotFound === false) {
            error_log((string) $e);
        }

        if (headers_sent() === false) {
            http_response_code($status);
        }

        Request::wantsJson()
            ? $this->renderJson($status, $e)
            : $this->renderHtml($status, $e);
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

    private function renderJson(int $status, Throwable $e): void
    {
        Response::json([
            'success' => false,
            'message' => $this->messageFor($status, $e),
        ], $status);
    }

    private function renderHtml(int $status, Throwable $e): void
    {
        if (headers_sent() === false) {
            header('Content-Type: text/html; charset=utf-8');
        }

        $title = $status === 404 ? '404 — Not Found' : '500 — Error';

        ob_start();
        echo '<h1>' . htmlspecialchars($title) . '</h1>';
        echo '<p>' . htmlspecialchars($this->messageFor($status, $e)) . '</p>';
        if ($status === 500 && $this->debug) {
            echo '<pre>' . htmlspecialchars((string) $e) . '</pre>';
        }
        $content = ob_get_clean();

        // Render inside the shared layout for a consistent look. Fall back to
        // the bare content if the layout itself can't be rendered.
        try {
            require __DIR__ . '/Views/layout.php';
        } catch (Throwable) {
            echo $content;
        }
    }

    /**
     * User-facing message for a given status. 404 messages are our own and
     * safe to show; 500 details are hidden unless debug is on.
     */
    private function messageFor(int $status, Throwable $e): string
    {
        if ($status === 404) {
            return $e->getMessage() !== '' ? $e->getMessage() : 'Not found.';
        }

        return $this->debug ? $e->getMessage() : 'Something went wrong.';
    }
}
