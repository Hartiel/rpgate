import { createRouter, createWebHistory } from 'vue-router';
import WelcomePage from '@/Pages/WelcomePage.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import SystemLayout from '@/Layouts/SystemLayout.vue';
import RegisterPage from '@/Pages/Auth/RegisterPage.vue';
import LoginPage from '@/Pages/Auth/LoginPage.vue';
import HomePage from '@/Pages/System/HomePage.vue';
import SettingsPage from '@/Pages/System/SettingsPage.vue';
import ProfilePage from '@/Pages/System/ProfilePage.vue';
import { useAuthStore } from '@/stores/authStore';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'welcome',
            component: WelcomePage,
            meta: {
                title: 'Welcome to RPGate'
            }
        },
        {
            path: '/auth',
            component: AuthLayout,
            children: [
                {
                    path: 'register',
                    name: 'register',
                    component: RegisterPage,
                    meta: { title: 'Register - RPGate', requiresGuest: true }
                },
                {
                    path: 'login',
                    name: 'login',
                    component: LoginPage,
                    meta: {
                        title: 'Log in - RPGate',
                        requiresGuest: true,
                    }
                },
            ],
        },
        {
            path: '/',
            component: SystemLayout,
            children: [
                {
                    path: 'home', // The URL will be /home
                    name: 'home',
                    component: HomePage,
                    meta: { title: 'Dashboard - RPGate', requiresAuth: true }
                },
                {
                    path: 'settings',
                    name: 'settings',
                    component: SettingsPage,
                    meta: { title: 'Settings - RPGate', requiresAuth: true }
                },
                {
                    path: 'profile',
                    name: 'profile',
                    component: ProfilePage,
                    meta: { title: 'Profile - RPGate', requiresAuth: true }
                }
            ],
        },
    ],
});

router.beforeEach(async (to) => {
    document.title = (to.meta.title as string) || 'RPGate';

    const authStore = useAuthStore();

    if (!authStore.isInitialized) {
        await authStore.checkAuth();
    }

    // 1. Guest trying to access a restricted page (e.g., Home) -> Redirect to Log in
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return { name: 'login' };
    }

    // 2. Authenticated user trying to access a guest page (e.g., Login) -> Redirect to Home
    if (to.meta.requiresGuest && authStore.isAuthenticated) {
        return { name: 'home' };
    }
});

export default router;
