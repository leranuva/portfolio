import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import intersect from '@alpinejs/intersect';

Alpine.plugin(persist);
Alpine.plugin(intersect);

Alpine.store('theme', {
    dark: (() => {
        if (typeof localStorage === 'undefined') return false;
        const stored = localStorage.getItem('portfolio-dark');
        if (stored !== null) return stored === 'true';
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    })(),
    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('portfolio-dark', this.dark);
    },
});

window.Alpine = Alpine;
Alpine.start();
