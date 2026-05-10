<?php
namespace App\Controllers;

abstract class BaseController {

    // -------------------------------------------------------------------------
    // Renderizado de vistas
    // -------------------------------------------------------------------------

    /**
     * Carga una vista pasándole variables de forma explícita.
     * El controlador decide qué datos ve la vista — nunca al revés.
     *
     * @param string $view  Ruta relativa a app/Views/ sin extensión. Ej: 'menu', 'admin/dashboard'
     * @param array  $data  Variables que estarán disponibles dentro de la vista.
     */
    protected function render(string $view, array $data = []): void {
        extract($data);  // convierte ['dishes' => [...]] en $dishes
        require_once __DIR__ . "/../Views/{$view}.php";
    }

    // -------------------------------------------------------------------------
    // Redirecciones
    // -------------------------------------------------------------------------

    /**
     * Redirige a una action del router y detiene la ejecución.
     */
    protected function redirect(string $action): void {
        header("Location: " . URL . "?action={$action}");
        exit();
    }

    /**
     * Redirige con un parámetro extra en la URL. Útil para confirmaciones.
     * Ej: redirectWith('orders', 'confirmed', $orderId)
     */
    protected function redirectWith(string $action, string $key, string $value): void {
        header("Location: " . URL . "?action={$action}&{$key}={$value}");
        exit();
    }

    // -------------------------------------------------------------------------
    // Respuestas HTTP
    // -------------------------------------------------------------------------

    /**
     * Termina la ejecución con un código HTTP y un mensaje simple.
     * Útil para errores que no necesitan una vista completa.
     */
    protected function abort(int $code, string $message = ''): void {
        http_response_code($code);
        echo "<h1>{$code} — {$message}</h1>";
        exit();
    }
}
