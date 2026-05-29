<?php ob_start(); ?>

<section class="min-h-[85vh] flex items-center justify-center p-6 bg-gray-50">
    <div class="bg-white rounded-[3rem] shadow-2xl p-12 max-w-md w-full border border-gray-100 relative overflow-hidden">

        <!-- Franja verde superior -->
        <div class="absolute top-0 left-0 w-full h-2 bg-[#1a4731]"></div>

        <!-- Logo / Título -->
        <div class="text-center mb-10">
            <div class="flex justify-center mb-6">
                <img src="img/logoRestaurantGreen.png" alt="Biconoir" class="h-16 w-auto" style="filter: none;">
            </div>
            <h2 class="text-3xl font-bold text-gray-800 uppercase tracking-tighter">Welcome</h2>
            <p class="text-gray-400 text-sm mt-2 italic">Access your gourmet account with Google.</p>
        </div>

        <!-- Mensaje de error OAuth (si hubo alguno) -->
        <?php if (isset($_SESSION['oauth_error'])): ?>
            <div class="bg-red-50 text-red-500 p-4 rounded-2xl mb-6 text-sm font-bold border border-red-100">
                ⚠️ <?php echo htmlspecialchars($_SESSION['oauth_error']); ?>
            </div>
            <?php unset($_SESSION['oauth_error']); ?>
        <?php endif; ?>

        <!-- Separador -->
        <div class="flex items-center gap-4 mb-8">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Continue with</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <!-- ─────────────────────────────────────────────────────────────────
             Botón Google OAuth
             FRONT END: Al hacer clic, redirige a googleRedirect() que construye
             la URL de Google con:
               - client_id  → identifica nuestra aplicación en Google
               - state      → token anti-CSRF generado aleatoriamente
               - scope      → permisos solicitados (openid, email, profile)
               - redirect_uri → dónde Google enviará el código de autorización
        ──────────────────────────────────────────────────────────────────── -->
        <a href="index.php?action=oauth_redirect"
           class="flex items-center justify-center gap-4 w-full border-2 border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300 text-gray-700 font-bold py-4 px-6 rounded-2xl transition-all duration-200 shadow-sm hover:shadow-md group">

            <!-- Ícono oficial de Google -->
            <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>

            <span class="text-base group-hover:text-[#1a4731] transition-colors">
                Continue with Google
            </span>
        </a>

        <!-- Aviso informativo -->
        <p class="text-center text-xs text-gray-400 mt-8 leading-relaxed">
            By continuing, you agree that Biconoir stores your name and email<br>
            to manage your account. We do not store passwords.
        </p>

        <!-- Badge OAuth -->
        <div class="flex items-center justify-center gap-2 mt-6">
            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span class="text-xs text-gray-400 font-medium">Secure authentication with OAuth 2.0</span>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
