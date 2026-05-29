<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biconoir's Restaurant — OAuth Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="flex flex-col min-h-screen antialiased">

    <header class="bg-[#1a4731] text-white shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php?action=<?php echo isset($_SESSION['user']) ? 'menu' : 'login'; ?>" class="flex items-center">
                <img src="img/logoRestaurantGreen.png" alt="Biconoir" class="logo-main">
            </a>

            <!-- Navegación: solo si hay sesión activa -->
            <?php if (isset($_SESSION['user'])): ?>
            <nav class="hidden md:flex items-center space-x-6">

                <!-- Contador de sesión -->
                <div id="session-timer-container" class="flex items-center gap-3 bg-black/20 px-4 py-2 rounded-full border border-white/10">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-green-300">Session:</span>
                    <span id="session-timer" class="font-bold text-white tabular-nums text-sm">--:--</span>
                </div>

                <!-- Usuario -->
                <div class="flex items-center gap-2 bg-black/20 px-4 py-2 rounded-full border border-white/10">
                    <span class="text-sm font-bold"><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                </div>

                <!-- Botón de cerrar sesión -->
                <a href="index.php?action=logout&manual=1"
                   class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 shadow-lg hover:shadow-red-900/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </a>
            </nav>
            <?php else: ?>
            <a href="index.php?action=login"
               class="bg-white text-[#1a4731] px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-gray-100 transition-all">
                Sign In
            </a>
            <?php endif; ?>
        </div>
    </header>

    <main class="flex-grow">
        <?php echo $content; ?>
    </main>

    <footer class="bg-gray-900 text-gray-500 py-10 text-center text-xs">
        <div class="container mx-auto px-6">
            <p class="mb-2 uppercase tracking-widest font-bold">&copy; 2026 Biconoir's Restaurant</p>
            <p class="italic">Excellence in every culinary detail.</p>
        </div>
    </footer>

    <?php if (isset($_SESSION['user'])): ?>
    <!-- Script del contador de sesión — solo se carga si hay sesión activa -->
    <script src="js/session-timer.js"></script>
    <?php endif; ?>

</body>
</html>
