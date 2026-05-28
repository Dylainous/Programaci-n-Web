<?php ob_start();

// Determinamos de dónde venía el usuario para personalizar el mensaje
$from = $_GET['from'] ?? 'menu';

$messages = [
    'cart'     => ['icon' => '🛒', 'page' => 'el carrito',   'url' => 'index.php?action=cart'],
    'checkout' => ['icon' => '🧾', 'page' => 'el checkout',  'url' => 'index.php?action=checkout'],
    'orders'   => ['icon' => '📦', 'page' => 'tus pedidos',  'url' => 'index.php?action=orders'],
];

$info = $messages[$from] ?? ['icon' => '🔒', 'page' => 'esa sección', 'url' => 'index.php?action=menu'];
?>

<section class="min-h-[85vh] flex items-center justify-center p-6 bg-gray-50">
    <div class="bg-white rounded-[3rem] shadow-2xl p-12 max-w-lg w-full border border-gray-100 relative overflow-hidden text-center">

        <!-- Franja roja superior (indica acceso denegado) -->
        <div class="absolute top-0 left-0 w-full h-2 bg-red-400"></div>

        <!-- Ícono principal -->
        <div class="text-8xl mb-6 select-none"><?php echo $info['icon']; ?></div>

        <!-- Título -->
        <h2 class="text-3xl font-bold text-gray-800 uppercase tracking-tighter mb-4">
            Sesión Requerida
        </h2>

        <!-- Mensaje explicativo -->
        <p class="text-gray-500 text-base leading-relaxed mb-2">
            Intentaste acceder a <span class="font-bold text-[#1a4731]"><?php echo $info['page']; ?></span>,
            pero tu sesión ha expirado o no has iniciado sesión.
        </p>
        <p class="text-gray-400 text-sm italic mb-10">
            Inicia sesión con Google para continuar.
        </p>

        <!-- Botones de acción -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">

            <!-- Botón principal: ir al login -->
            <a href="index.php?action=login"
               class="flex items-center justify-center gap-3 bg-[#1a4731] text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-black transition-all duration-200 hover:scale-[1.02]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#fff"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#fff"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#fff"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#fff"/>
                </svg>
                Iniciar sesión
            </a>

            <!-- Botón secundario: volver al menú -->
            <a href="index.php?action=menu"
               class="flex items-center justify-center gap-2 border-2 border-gray-200 text-gray-600 px-8 py-4 rounded-2xl font-bold hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                🍽️ Ver el Menú
            </a>

        </div>

        <!-- Nota de seguridad -->
        <p class="text-xs text-gray-300 mt-10">
            Por tu seguridad, las sesiones se cierran automáticamente al cerrar el navegador.
        </p>

    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
