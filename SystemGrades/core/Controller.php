<?php

/**
 * Controller - Base class
 * Provides view rendering helpers for all controllers.
 */
abstract class Controller
{
    /**
     * Render a view file.
     *
     * @param string $view   Dot-separated path relative to app/views/ (e.g. 'students.list')
     * @param array  $data   Variables to extract into the view scope
     */
    protected function view(string $view, array $data = []): void
    {
        // Convert dot notation to directory separator
        $path = str_replace('.', DIRECTORY_SEPARATOR, $view);
        $file = __DIR__ . '/../app/views/' . $path . '.php';

        if (!file_exists($file)) {
            die("View not found: {$file}");
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    /** Redirect to a URL (relative or absolute). */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /** Return a POST value sanitized, or $default if not set. */
    protected function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    /** Return a GET value sanitized, or $default if not set. */
    protected function get(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }
}
