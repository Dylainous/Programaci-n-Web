<?php
session_start();

// Cargar variables de entorno locales (solo existe en local, no en producción)
if (file_exists(__DIR__ . '/../config/env.php')) {
    require_once __DIR__ . '/../config/env.php';
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../config/oauth.php';   // ← Credenciales y constantes OAuth

use App\Controllers\AuthController;
use App\Controllers\MenuController;

$action = $_GET['action'] ?? 'login';

switch ($action) {

    // ── Autenticación OAuth Google ──────────────────────────────────────────

    case 'login':
        // Muestra la página de login (botón "Continuar con Google")
        (new AuthController())->login();
        break;

    case 'oauth_redirect':
        // FRONT END → redirige al usuario a Google para que se autentique
        // Construye la URL con: client_id, scope, state (anti-CSRF), redirect_uri
        (new AuthController())->googleRedirect();
        break;

    case 'oauth_callback':
        // BACK END → Google regresa aquí con el código de autorización
        // 1. Valida el state anti-CSRF
        // 2. Intercambia el code por un access_token (server-to-server)
        // 3. Obtiene datos del usuario desde Google
        // 4. Crea o recupera el usuario en la BD
        // 5. Guarda la sesión con login_time para el contador
        (new AuthController())->googleCallback();
        break;

    case 'logout':
        // Destruye la sesión. Con ?manual=1 → pantalla de confirmación.
        // Sin el parámetro → expiración automática → redirect_notice.
        (new AuthController())->logout();
        break;

    case 'logout_confirm':
        // Pantalla que confirma el cierre de sesión exitoso
        require_once __DIR__ . '/../app/Views/logout_confirm.php';
        break;

    case 'check_session':
        // Endpoint AJAX: devuelve JSON con {expired, remaining}
        // El JavaScript del contador llama a esto cada segundo
        (new AuthController())->checkSessionExpiry();
        break;

    // ── Menú (protegido) ────────────────────────────────────────────────────

    case 'menu':
        // Requiere sesión activa y no expirada.
        // Solo muestra los platos — sin carrito, sin pedidos.
        (new MenuController())->index();
        break;

    // ── Página de aviso de sesión requerida ─────────────────────────────────

    case 'redirect_notice':
        // Se muestra cuando alguien intenta acceder con sesión cerrada/expirada
        // o cuando pegó la URL del menú sin tener sesión activa.
        // Se redirige automáticamente al login después de 5 segundos.
        require_once __DIR__ . '/../app/Views/redirect_notice.php';
        break;

    default:
        // Cualquier otra ruta → al login
        header('Location: index.php?action=login');
        exit();
}
