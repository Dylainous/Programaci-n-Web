<?php

/**
 * index.php — Front Controller
 * All HTTP requests are routed here.
 * Usage: index.php?action=<action>
 */

declare(strict_types=1);

// ── Autoload core & app classes ──────────────────────────────────────────────
$autoloadDirs = [
    __DIR__ . '/core',
    __DIR__ . '/app/controllers',
    __DIR__ . '/app/models',
];

spl_autoload_register(function (string $class) use ($autoloadDirs): void {
    foreach ($autoloadDirs as $dir) {
        $file = $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ── Bootstrap ────────────────────────────────────────────────────────────────
// Make DB config available everywhere (needed by Database singleton)
require_once __DIR__ . '/config/database.php';

// ── Router ───────────────────────────────────────────────────────────────────
$action = $_GET['action'] ?? 'list';
$method = strtoupper($_SERVER['REQUEST_METHOD']);

$controller = new StudentController();

// Route map: [action => [GET handler, POST handler]]
$routes = [
    'list'   => ['GET'  => 'list'],
    'create' => ['GET'  => 'create'],
    'store'  => ['POST' => 'store'],
    'edit'   => ['GET'  => 'edit'],
    'update' => ['POST' => 'update'],
    'delete' => ['POST' => 'delete'],
];

if (isset($routes[$action][$method])) {
    $handler = $routes[$action][$method];
    $controller->$handler();
} elseif (isset($routes[$action])) {
    // Action exists but wrong method — redirect to list
    header('Location: index.php?action=list');
    exit;
} else {
    // Unknown action — 404
    http_response_code(404);
    echo '<h1>404 Not Found</h1><p><a href="index.php">Go home</a></p>';
}
