<?php

namespace App;

class Router
{
    /**
     * Registro de rutas.
     * Estructura: $routes[METHOD][action] = ['handler' => [...], 'middlewares' => [...]]
     */
    private array $routes = [];

    /**
     * Middlewares disponibles que el router puede ejecutar.
     */
    private array $middlewares = [
        'auth'  => [self::class, 'middlewareAuth'],
        'admin' => [self::class, 'middlewareAdmin'],
    ];

    // -------------------------------------------------------------------------
    // Registro de rutas
    // -------------------------------------------------------------------------

    public function get(string $action, array $handler, array $middlewares = []): void
    {
        $this->routes['GET'][$action] = [
            'handler'     => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function post(string $action, array $handler, array $middlewares = []): void
    {
        $this->routes['POST'][$action] = [
            'handler'     => $handler,
            'middlewares' => $middlewares,
        ];
    }

    // -------------------------------------------------------------------------
    // Despacho
    // -------------------------------------------------------------------------

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? 'home';

        $route = $this->routes[$method][$action] ?? null;

        // Si la acción no existe como GET, intentar como POST (para formularios
        // que redirigen a la misma action).
        if ($route === null && $method === 'POST') {
            $route = $this->routes['GET'][$action] ?? null;
        }

        if ($route === null) {
            http_response_code(404);
            echo "<h1>404 — Página no encontrada</h1>";
            return;
        }

        // Ejecutar middlewares antes de llegar al controlador
        foreach ($route['middlewares'] as $name) {
            $middleware = $this->middlewares[$name] ?? null;
            if ($middleware) {
                call_user_func($middleware);
            }
        }

        // Instanciar el controlador y llamar al método
        [$controllerClass, $controllerMethod] = $route['handler'];
        $controller = new $controllerClass();
        $controller->$controllerMethod();
    }

    // -------------------------------------------------------------------------
    // Middlewares
    // -------------------------------------------------------------------------

    /**
     * Requiere que el usuario esté autenticado.
     * Si no lo está, redirige al login conservando la action de destino.
     */
    private static function middlewareAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            $redirect = $_GET['action'] ?? 'home';
            header("Location: " . URL . "?action=login&redirect={$redirect}");
            exit();
        }
    }

    /**
     * Requiere que el usuario sea administrador.
     * Llama primero a auth para asegurar que hay sesión activa.
     */
    private static function middlewareAdmin(): void
    {
        self::middlewareAuth();

        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "<h1>403 — Acceso denegado</h1>";
            exit();
        }
    }
}