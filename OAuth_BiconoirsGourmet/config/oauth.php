<?php

// ─── Google OAuth 2.0 Configuration ───────────────────────────────────────────
// APP_URL se configura como variable de entorno en Render.
// En local puedes crear un archivo .env o definirla en tu servidor.
// Ejemplo Render: APP_URL = https://tu-app.onrender.com

$appUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/BiconoirsGourmet/public', '/');

define('GOOGLE_CLIENT_ID',     getenv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI',  $appUrl . '/index.php?action=oauth_callback');

define('GOOGLE_AUTH_URL',  'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token');
define('GOOGLE_USER_URL',  'https://www.googleapis.com/oauth2/v3/userinfo');