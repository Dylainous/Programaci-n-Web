<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {

    // ─────────────────────────────────────────────────────────────────────────
    // VISTA: Login / Sign Up
    // Muestra la página con el botón "Continuar con Google".
    // Si ya hay sesión activa, redirige al menú directamente.
    // ─────────────────────────────────────────────────────────────────────────
    public function login() {
        if (isset($_SESSION['user'])) {
            header('Location: index.php?action=menu');
            exit();
        }
        require_once __DIR__ . '/../Views/login.php';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PASO 1 (FRONT END): Redirige al usuario a Google para autenticarse.
    // Se genera un "state" aleatorio para prevenir ataques CSRF.
    // ─────────────────────────────────────────────────────────────────────────
    public function googleRedirect() {
        // Generamos un token de estado único y lo guardamos en sesión
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $params = http_build_query([
            'client_id'     => GOOGLE_CLIENT_ID,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'access_type'   => 'online',
            'prompt'        => 'select_account'  // Permite elegir cuenta cada vez
        ]);

        header('Location: ' . GOOGLE_AUTH_URL . '?' . $params);
        exit();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PASO 2 (BACK END): Google redirige aquí con un "code" de autorización.
    // Validamos el state, intercambiamos el code por un token,
    // consultamos los datos del usuario a Google y creamos la sesión.
    // ─────────────────────────────────────────────────────────────────────────
    public function googleCallback() {
        // ── Validación Back End #1: Verificar parámetros en la URL ──────────
        if (!isset($_GET['code']) || !isset($_GET['state'])) {
            $this->redirectToLoginWithError('Respuesta inválida de Google.');
            return;
        }

        // ── Validación Back End #2: Verificar el state anti-CSRF ────────────
        // Comprobamos que el state que devuelve Google es el mismo que
        // generamos nosotros. Esto evita ataques de falsificación de solicitud.
        if (!isset($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            $this->redirectToLoginWithError('Error de seguridad: state inválido.');
            return;
        }
        unset($_SESSION['oauth_state']); // Ya no lo necesitamos

        // ── Back End: Intercambiar el "code" por un access_token ─────────────
        $tokenData = $this->exchangeCodeForToken($_GET['code']);

        if (!$tokenData || !isset($tokenData['access_token'])) {
            $this->redirectToLoginWithError('No se pudo obtener el token de Google.');
            return;
        }

        // ── Back End: Obtener datos del usuario desde Google ─────────────────
        $googleUser = $this->fetchGoogleUserInfo($tokenData['access_token']);

        if (!$googleUser || !isset($googleUser['sub'])) {
            $this->redirectToLoginWithError('No se pudieron obtener los datos del usuario.');
            return;
        }

        // ── Back End: Buscar o crear el usuario en nuestra base de datos ──────
        $user = User::findOrCreateFromGoogle($googleUser);

        // ── Crear la sesión del usuario ───────────────────────────────────────
        $_SESSION['user'] = $user;

        // Redirigir al menú (o a la página que intentaba acceder)
        $redirect = $_SESSION['intended_url'] ?? 'index.php?action=menu';
        unset($_SESSION['intended_url']);

        header('Location: ' . $redirect);
        exit();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // LOGOUT: Destruye la sesión y redirige al login.
    // ─────────────────────────────────────────────────────────────────────────
    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS PRIVADOS DE APOYO
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Hace la petición POST a Google para intercambiar el código de autorización
     * por un access_token. Esto ocurre completamente en el servidor (Back End).
     */
    private function exchangeCodeForToken(string $code): ?array {
        $postData = http_build_query([
            'code'          => $code,
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'grant_type'    => 'authorization_code'
        ]);

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);

        $response = @file_get_contents(GOOGLE_TOKEN_URL, false, $context);
        return $response ? json_decode($response, true) : null;
    }

    /**
     * Consulta la API de Google con el access_token para obtener
     * los datos del usuario: sub, name, email, picture.
     */
    private function fetchGoogleUserInfo(string $accessToken): ?array {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $accessToken
            ]
        ]);

        $response = @file_get_contents(GOOGLE_USER_URL, false, $context);
        return $response ? json_decode($response, true) : null;
    }

    /**
     * Redirige al login con un mensaje de error en sesión.
     */
    private function redirectToLoginWithError(string $message): void {
        $_SESSION['oauth_error'] = $message;
        header('Location: index.php?action=login');
        exit();
    }
}
