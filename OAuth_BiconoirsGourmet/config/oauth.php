<?php

// ─── Google OAuth 2.0 Configuration ───────────────────────────────────────────
// APP_URL se configura como variable de entorno en producción.
// En local se carga desde config/env.php

$appUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/OAuth_Biconoir_Simple/public', '/');

define('GOOGLE_CLIENT_ID',     getenv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI',  $appUrl . '/index.php?action=oauth_callback');

define('GOOGLE_AUTH_URL',  'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token');
define('GOOGLE_USER_URL',  'https://www.googleapis.com/oauth2/v3/userinfo');

// ─── Duración de la sesión (en segundos) ──────────────────────────────────────
// El menú mostrará un contador regresivo de este tiempo.
// Al expirar, la sesión se destruye automáticamente.
define('SESSION_LIFETIME', 60); // 60 segundos = 1 minuto
