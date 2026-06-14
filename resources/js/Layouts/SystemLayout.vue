<script setup lang="ts">
import { useAuthStore } from '@/stores/authStore';
import { useRouter, useRoute } from 'vue-router';
import AppLogo from '@/Components/AppLogo.vue';
import {
    Wand2Icon,
    UserIcon,
    ScrollIcon,
    SettingsIcon,
    LogOutIcon
} from '@lucide/vue';

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const handleLogout = async () => {
    await authStore.logout();
    await router.push({ name: 'login' });
};

const navItems = [
    { name: 'Lobby', icon: Wand2Icon, route: 'home' },
    { name: 'Profile', icon: UserIcon, route: 'profile' },
    { name: 'My Campaigns', icon: ScrollIcon, route: '#' },
];
</script>

<template>
    <div class="flex min-h-screen bg-rpgate-bg text-rpgate-text font-sans selection:bg-rpgate-cta/30">
        <!-- Sidebar -->
        <aside class="w-64 border-r border-rpgate-card-border flex flex-col fixed inset-y-0 left-0 z-50 bg-rpgate-bg">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-10">
                    <div class="text-rpgate-cta">
                        <AppLogo class="w-8 h-8" />
                    </div>
                    <div>
                        <h1 class="text-xl font-serif font-bold tracking-tight text-rpgate-cta leading-none">RPGate</h1>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-rpgate-text/40 mt-1">Digital Alchemist</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <router-link
                        v-for="item in navItems"
                        :key="item.name"
                        :to="item.route !== '#' ? { name: item.route } : '#'"
                        class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200 group"
                        :class="route.name === item.route ? 'bg-linear-to-r from-rpgate-cta/20 to-transparent text-rpgate-cta border-l-2 border-rpgate-cta shadow-[inset_0_0_20px_rgba(230,137,26,0.05)]' : 'text-rpgate-text/60 hover:text-rpgate-header hover:bg-rpgate-card-bg'"
                    >
                        <component 
                            :is="item.icon" 
                            :size="18" 
                            class="transition-transform group-hover:scale-110" 
                        />
                        <span class="font-medium tracking-wide">{{ item.name }}</span>
                    </router-link>
                </nav>
            </div>

            <div class="mt-auto p-6 space-y-4">
                <router-link
                    :to="{ name: 'settings' }"
                    class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="route.name === 'settings' ? 'bg-linear-to-r from-rpgate-cta/20 to-transparent text-rpgate-cta border-l-2 border-rpgate-cta shadow-[inset_0_0_20px_rgba(230,137,26,0.05)]' : 'text-rpgate-text/60 hover:text-rpgate-header hover:bg-rpgate-card-bg'"
                >
                    <SettingsIcon class="text-rpgate-accent" :size="18" />
                    <span class="font-medium tracking-wide">Settings</span>
                </router-link>

                <div class="pt-4 border-t border-rpgate-card-border">
                    <div class="flex items-center gap-3 p-2 rounded-lg bg-rpgate-card-bg border border-rpgate-card-border">
                        <div class="w-10 h-10 rounded-lg bg-linear-to-br from-rpgate-accent/20 to-rpgate-cta/20 flex items-center justify-center border border-rpgate-card-border overflow-hidden shadow-inner">
                            <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-full h-full object-cover" />
                            <UserIcon></UserIcon>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-sm font-bold truncate text-rpgate-header leading-tight">
                                {{ authStore.user?.name || 'Alaric Thorne' }}
                            </p>
                            <p class="text-[10px] text-rpgate-text/40 uppercase tracking-wider">
                                Lvl 14 Wizard
                            </p>
                        </div>
                        <button @click="handleLogout" class="text-rpgate-text/20 hover:text-red-600 transition-colors" aria-label="Logout Button">
                            <LogOutIcon></LogOutIcon>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 ml-64 flex flex-col">
            <!-- Topbar -->
            <header class="h-20 border-b border-rpgate-card-border flex items-center justify-between px-12 sticky top-0 z-40 rpgate-bg/80 backdrop-blur-md">
                <div class="flex items-center gap-4 text-sm font-medium">
                    <span class="text-rpgate-accent italic">Campaign: The Shadow Rift</span>
                    <span class="text-rpgate-text/20">|</span>
                    <span class="flex items-center gap-2 text-rpgate-text/60">
                        Status: <span class="text-green-500 flex items-center gap-1.5 font-bold tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Active
                        </span>
                    </span>
                </div>

                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-4 text-rpgate-text/40">
                        <button class="hover:text-rpgate-header transition-colors"><i class="fa-solid fa-backward-step"></i></button>
                        <button class="w-8 h-8 rounded-full border border-rpgate-card-border flex items-center justify-center hover:bg-rpgate-card-bg hover:text-rpgate-header transition-all shadow-sm">
                            <i class="fa-solid fa-pause text-xs"></i>
                        </button>
                        <button class="hover:text-rpgate-header transition-colors"><i class="fa-solid fa-forward-step"></i></button>
                    </div>

                    <div class="flex items-center gap-3 px-4 py-2 bg-rpgate-card-bg rounded-lg border border-rpgate-card-border shadow-inner">
                        <span class="text-[10px] text-rpgate-accent uppercase tracking-widest font-bold">Dungeon Synth - Track 04</span>
                        <div class="w-6 h-6 rounded bg-rpgate-accent/10 flex items-center justify-center border border-rpgate-accent/10">
                            <i class="fa-solid fa-music text-[10px] text-rpgate-accent"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-rpgate-bg">
                <router-view />
            </main>
        </div>
    </div>
</template>

<style scoped>
main::-webkit-scrollbar {
    width: 8px;
}
main::-webkit-scrollbar-track {
    background: transparent;
}
main::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}
main::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>
