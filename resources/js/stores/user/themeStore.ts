// @/stores/user/themeStore.ts (Ou onde quer que ela esteja)
import { defineStore } from 'pinia';
import { ref } from 'vue';

export type Theme = 'light' | 'dark' | 'system';

export const useThemeStore = defineStore('theme', () => {
    // 🎯 O estado passa a ser uma ref simples, controlada externamente
    const theme = ref<Theme>('system');

    const applyThemeToDOM = (targetTheme: Theme) => {
        const root = document.documentElement;
        root.classList.remove('light', 'dark');
        
        if (targetTheme === 'system') {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.classList.add(isDark ? 'dark' : 'light');
        } else {
            root.classList.add(targetTheme);
        }
    };

    // 🎯 Define o tema na store e no DOM (usado tanto por visitantes quanto pelo subscriber)
    const setTheme = (newTheme: Theme) => {
        theme.value = newTheme;
        applyThemeToDOM(newTheme);
    };

    // 🎯 Listener para detectar mudanças de tema no sistema operacional
    if (typeof window !== 'undefined') {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const handleSystemThemeChange = () => {
            if (theme.value === 'system') {
                applyThemeToDOM('system');
            }
        };
        // Suporte a browsers modernos e antigos
        if (mediaQuery.addEventListener) {
            mediaQuery.addEventListener('change', handleSystemThemeChange);
        } else if ((mediaQuery as any).addListener) {
            (mediaQuery as any).addListener(handleSystemThemeChange);
        }
    }

    return {
        theme,
        setTheme,
        applyThemeToDOM,
    };
});