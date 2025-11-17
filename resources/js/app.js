import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide'; // ✅ Proper Lucide import

window.Alpine = Alpine;
Alpine.start();

// ✅ Initialize Lucide icons after DOM loads
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});