
window.toggleSidebar = function() {
    document.getElementById('menu_sidebar').classList.toggle('open');
};

(function bindCombatLogSwipe() {
    const EDGE_WIDTH = 30;
    const SWIPE_THRESHOLD = 50;
    let startX = null;
    let startY = null;
    let startedOpen = false;

    document.addEventListener('touchstart', e => {
        const touch = e.touches[0];
        startedOpen = document.getElementById('menu_sidebar').classList.contains('open');
        if (startedOpen || touch.clientX <= EDGE_WIDTH) {
            startX = touch.clientX;
            startY = touch.clientY;
        } else {
            startX = null;
            startY = null;
        }
    }, { passive: true });

    document.addEventListener('touchend', e => {
        if (startX === null) {
            return;
        }
        const touch = e.changedTouches[0];
        const dx = touch.clientX - startX;
        const dy = touch.clientY - startY;
        startX = null;
        startY = null;
        if (Math.abs(dx) < SWIPE_THRESHOLD || Math.abs(dx) < Math.abs(dy)) {
            return;
        }
        const drawer = document.getElementById('menu_sidebar');
        if (dx > 0 && !startedOpen) {
            drawer.classList.add('open');
        } else if (dx < 0 && startedOpen) {
            drawer.classList.remove('open');
        }
    }, { passive: true });
})();
