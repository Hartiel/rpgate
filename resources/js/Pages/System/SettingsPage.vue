<script setup lang="ts">
import { useThemeStore, type Theme } from '@/stores/user/themeStore';
import { PaletteIcon, SunIcon, MoonIcon, ComputerIcon } from "@lucide/vue";

const themeStore = useThemeStore();

const handleThemeChange = (chosenTheme: Theme) => {
    themeStore.setTheme(chosenTheme);
};
</script>

<template>
    <div class="p-12 max-w-4xl mx-auto space-y-10 pb-24">
        <header class="space-y-4">
            <h2 class="text-[10px] uppercase tracking-[0.3em] font-bold text-rpgate-cta">Configuration</h2>
            <h1 class="text-5xl font-serif text-rpgate-header italic tracking-tight">Realm Settings</h1>
            <p class="text-rpgate-text/60 max-w-2xl text-base leading-relaxed">
                Configure your environment, adjust the visuals of your interface, and fine-tune your connection to the rift.
            </p>
        </header>

        <section class="rpg-card p-2 space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-rpgate-accent/10 flex items-center justify-center mb-8 border border-rpgate-accent/10">
                    <PaletteIcon class="text-rpgate-accent" :size="18" />
                </div>
                <div>
                    <h3 class="text-xl font-serif font-bold text-rpgate-header">Visual Interface Theme</h3>
                    <p class="text-rpgate-text/40 text-xs mt-1">Select how the realm appears on your screen.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                <button
                    @click="handleThemeChange('light')"
                    data-testid="theme-light-btn"
                    class="group relative flex flex-col items-center gap-4 p-6 rounded-xl border transition-all text-center"
                    :class="themeStore.theme === 'light' 
                        ? 'border-rpgate-cta bg-rpgate-cta/5 shadow-[0_0_15px_rgba(230,137,26,0.15)] text-rpgate-cta' 
                        : 'border-rpgate-input-border bg-rpgate-input-bg text-rpgate-text/60 hover:border-rpgate-accent/50 hover:text-rpgate-text'"
                >
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-rpgate-card-bg border border-rpgate-card-border group-hover:scale-110 transition-transform">
                        <SunIcon class="text-rpgate-accent" :size="18" />
                    </div>
                    <div>
                        <span class="font-bold text-xs uppercase tracking-wider block">Light Theme</span>
                        <span class="text-[10px] text-rpgate-text/40 block mt-1">Searing glow of the high sun</span>
                    </div>
                </button>

                <button
                    @click="handleThemeChange('dark')"
                    data-testid="theme-dark-btn"
                    class="group relative flex flex-col items-center gap-4 p-6 rounded-xl border transition-all text-center"
                    :class="themeStore.theme === 'dark' 
                        ? 'border-rpgate-cta bg-rpgate-cta/5 shadow-[0_0_15px_rgba(230,137,26,0.15)] text-rpgate-cta' 
                        : 'border-rpgate-input-border bg-rpgate-input-bg text-rpgate-text/60 hover:border-rpgate-accent/50 hover:text-rpgate-text'"
                >
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-rpgate-card-bg border border-rpgate-card-border group-hover:scale-110 transition-transform">
                        <MoonIcon class="text-rpgate-accent" :size="18" />
                    </div>
                    <div>
                        <span class="font-bold text-xs uppercase tracking-wider block">Dark Theme</span>
                        <span class="text-[10px] text-rpgate-text/40 block mt-1">Deep shadows of the abyss</span>
                    </div>
                </button>

                <button
                    @click="handleThemeChange('system')"
                    data-testid="theme-system-btn"
                    class="group relative flex flex-col items-center gap-4 p-6 rounded-xl border transition-all text-center"
                    :class="themeStore.theme === 'system' 
                        ? 'border-rpgate-cta bg-rpgate-cta/5 shadow-[0_0_15px_rgba(230,137,26,0.15)] text-rpgate-cta' 
                        : 'border-rpgate-input-border bg-rpgate-input-bg text-rpgate-text/60 hover:border-rpgate-accent/50 hover:text-rpgate-text'"
                >
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-rpgate-card-bg border border-rpgate-card-border group-hover:scale-110 transition-transform">
                        <ComputerIcon class="text-rpgate-accent" :size="18" />
                    </div>
                    <div>
                        <span class="font-bold text-xs uppercase tracking-wider block">System Theme</span>
                        <span class="text-[10px] text-rpgate-text/40 block mt-1">Harmonize with the outer cosmos</span>
                    </div>
                </button>
            </div>
        </section>
    </div>
</template>