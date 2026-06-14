import { useThemeStore } from '@/stores/user/themeStore';
import { useUserStore } from '@/stores/user/userStore';
import { useAuthStore } from '@/stores/authStore';
import { watch } from 'vue';

/**
 * Initializes state synchronization subscribers for the application.
 * Called after Pinia is registered to avoid early activation issues.
 */
export function initSubscribers() {
    const themeStore = useThemeStore();
    const userStore = useUserStore();
    const authStore = useAuthStore();

    // Automatically sync theme changes to storage or backend
    watch(
        () => themeStore.theme,
        async (newTheme) => {
            if (authStore.isAuthenticated) {
                try {
                    await userStore.updateSettings({
                        theme: newTheme,
                        compact_mode: authStore.user?.settings?.compact_mode ?? false,
                    });
                } catch (error) {
                    console.error('Failed to synchronize theme settings with the server:', error);
                }
            } else {
                localStorage.setItem('theme', newTheme);
            }
        }
    );
}