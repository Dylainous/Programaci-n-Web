/**
 * session-timer.js — Contador de sesión OAuth
 *
 * Este script es el núcleo del temporizador de sesión.
 * Se ejecuta mientras el usuario está en el menú con sesión activa.
 *
 * Funcionamiento:
 * 1. Cada segundo consulta al servidor (check_session) el tiempo restante.
 * 2. Actualiza los contadores en el header y en el banner.
 * 3. Cuando el tiempo llega a 0, redirige a redirect_notice (sesión expirada).
 * 4. Aviso visual (color rojo) cuando quedan menos de 15 segundos.
 */

(function () {
    'use strict';

    // Elementos del DOM
    const headerTimer = document.getElementById('session-timer');
    const bannerTime  = document.getElementById('banner-time');
    const bannerBox   = document.getElementById('banner-timer');

    /**
     * Formatea segundos en MM:SS
     */
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    /**
     * Aplica estilos de urgencia cuando quedan pocos segundos
     */
    function applyUrgencyStyles(remaining) {
        if (remaining <= 15) {
            // Rojo urgente
            if (headerTimer) {
                headerTimer.classList.add('text-red-400');
                headerTimer.classList.remove('text-white');
            }
            if (bannerTime) {
                bannerTime.classList.add('text-red-600');
                bannerTime.classList.remove('text-[#1a4731]');
            }
            if (bannerBox) {
                bannerBox.classList.add('border-red-300', 'bg-red-50');
                bannerBox.classList.remove('border-gray-200');
            }
        } else {
            // Colores normales
            if (headerTimer) {
                headerTimer.classList.remove('text-red-400');
                headerTimer.classList.add('text-white');
            }
            if (bannerTime) {
                bannerTime.classList.remove('text-red-600');
                bannerTime.classList.add('text-[#1a4731]');
            }
            if (bannerBox) {
                bannerBox.classList.remove('border-red-300', 'bg-red-50');
                bannerBox.classList.add('border-gray-200');
            }
        }
    }

    /**
     * Consulta al servidor el tiempo restante de sesión.
     * Si expiró, redirige a la página de aviso.
     */
    async function checkSession() {
        try {
            const response = await fetch('index.php?action=check_session', {
                method: 'GET',
                cache: 'no-store',
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error('Error de red');

            const data = await response.json();

            if (data.expired) {
                // Sesión expirada en el servidor → redirigir
                clearInterval(timerInterval);
                window.location.href = 'index.php?action=redirect_notice&from=expired';
                return;
            }

            const remaining = Math.max(0, Math.floor(data.remaining));
            const formatted  = formatTime(remaining);

            if (headerTimer) headerTimer.textContent = formatted;
            if (bannerTime)  bannerTime.textContent  = formatted;

            applyUrgencyStyles(remaining);

            if (remaining <= 0) {
                clearInterval(timerInterval);
                window.location.href = 'index.php?action=redirect_notice&from=expired';
            }

        } catch (error) {
            // Error de red: mostrar guiones pero no redirigir todavía
            console.warn('Error al verificar sesión:', error);
        }
    }

    // Ejecutar inmediatamente y luego cada segundo
    checkSession();
    const timerInterval = setInterval(checkSession, 1000);

})();
