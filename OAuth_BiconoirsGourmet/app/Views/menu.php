<?php ob_start(); ?>

<!-- Banner de sesión con contador visual -->
<div class="bg-[#1a4731]/5 border-b border-[#1a4731]/10 py-4">
    <div class="container mx-auto px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-[#1a4731]/10 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-[#1a4731]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Sesión activa</p>
                <p class="text-sm font-bold text-[#1a4731]"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
            </div>
        </div>
        <div id="banner-timer" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-full shadow-sm">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs text-gray-500 font-medium">Tiempo restante:</span>
            <span id="banner-time" class="text-sm font-bold text-[#1a4731] tabular-nums">--:--</span>
        </div>
    </div>
</div>

<section class="py-20 container mx-auto px-6">
    <div class="text-center mb-16">
        <h2 class="text-6xl font-bold text-gray-800 mb-4 tracking-tighter uppercase">Nuestra Carta</h2>
        <p class="text-gray-400 italic text-lg">Descubre la excelencia en cada plato.</p>
        <div class="h-1.5 w-24 bg-[#1a4731] mx-auto mt-6 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
        <?php foreach ($dishes as $dish): ?>
            <div class="bg-white rounded-[3rem] overflow-hidden shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-500 flex flex-col group relative">
                <div class="h-64 overflow-hidden relative">
                    <img src="<?php echo htmlspecialchars($dish['image']); ?>"
                         alt="<?php echo htmlspecialchars($dish['name']); ?>"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-all"></div>
                    <!-- Precio sobre la imagen -->
                    <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-md px-5 py-1.5 rounded-full font-bold text-[#1a4731] shadow-2xl text-sm">
                        $<?php echo number_format($dish['price'], 2); ?>
                    </div>
                    <!-- Badge categoría -->
                    <?php if (!empty($dish['category'])): ?>
                    <div class="absolute bottom-4 left-4 bg-[#1a4731]/80 text-white text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm">
                        <?php echo htmlspecialchars($dish['category']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold text-gray-800 mb-3 tracking-tight group-hover:text-[#1a4731] transition-colors">
                        <?php echo htmlspecialchars($dish['name']); ?>
                    </h3>
                    <p class="text-gray-500 text-sm flex-grow leading-relaxed italic">
                        <?php echo htmlspecialchars($dish['description']); ?>
                    </p>
                    <!-- Línea separadora al pie -->
                    <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-2xl font-bold text-[#1a4731]">$<?php echo number_format($dish['price'], 2); ?></span>
                        <span class="text-xs text-gray-400 font-medium uppercase tracking-widest">Disponible</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($dishes)): ?>
    <div class="text-center py-20 text-gray-400">
        <div class="text-6xl mb-4">🍽️</div>
        <p class="text-xl font-bold">No hay platos disponibles en este momento.</p>
    </div>
    <?php endif; ?>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
