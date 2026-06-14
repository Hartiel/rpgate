import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';

import { initSubscribers } from '@/stores/subscribers';
import { useAuthStore } from '@/stores/authStore';
import { useThemeStore, type Theme } from '@/stores/user/themeStore';

async function bootstrap() {
    const app = createApp(App);
    const pinia = createPinia();

    app.use(pinia);
    app.use(router);

    // 1. Recover user session (simulating F5 persistent state check)
    const authStore = useAuthStore();
    try {
        await authStore.checkAuth(); // 🚀 Await Laravel response
    } catch (error) {
        console.warn('User is a guest or rift connection failed.');
    }

    // 2. Resolve initial theme settings (prioritizing backend settings over localStorage)
    const themeStore = useThemeStore();
    let initialTheme: Theme = 'system';

    if (authStore.isAuthenticated && authStore.user?.settings?.theme) {
        initialTheme = authStore.user.settings.theme;
    } else {
        const saved = localStorage.getItem('theme');
        if (saved === 'light' || saved === 'dark' || saved === 'system') {
            initialTheme = saved;
        }
    }
    
    // Apply initial theme immediately to prevent flashing
    themeStore.setTheme(initialTheme);

    // 3. Initialize background synchronization subscribers/watchers
    initSubscribers();

    // 4. Mount the application once state is fully restored
    app.mount('#app');
}

bootstrap();
