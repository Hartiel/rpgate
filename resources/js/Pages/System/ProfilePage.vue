<script setup lang="ts">
import { useAuthStore } from '@/stores/authStore';
import { UserIcon } from '@lucide/vue';

const authStore = useAuthStore();
</script>

<template>
    <div class="p-12 max-w-4xl mx-auto space-y-10 pb-24">
        <!-- Header -->
        <header class="space-y-4">
            <h2 class="text-[10px] uppercase tracking-[0.3em] font-bold text-rpgate-cta">User Identity</h2>
            <h1 class="text-5xl font-serif text-rpgate-header italic tracking-tight">Character Profile</h1>
            <p class="text-rpgate-text/40 max-w-2xl text-base leading-relaxed">
                Inspect your credentials, credentials within the guild, and your character stats in the realm.
            </p>
        </header>

        <!-- Profile Detail Card -->
        <section class="rpg-card flex flex-col md:flex-row gap-8 items-center p-8">
            <div class="w-32 h-32 rounded-2xl bg-linear-to-br from-rpgate-accent/20 to-rpgate-cta/20 flex items-center justify-center border border-rpgate-card-border overflow-hidden shadow-2xl relative group">
                <img v-if="authStore.user?.avatar" :src="authStore.user.avatar" class="w-full h-full object-cover" />
                <UserIcon class="w-16 h-16 text-rpgate-text/40" />
            </div>

            <div class="flex-1 space-y-4 text-center md:text-left">
                <div>
                    <h3 class="text-2xl font-serif font-bold text-rpgate-header">{{ authStore.user?.name || 'Alaric Thorne' }}</h3>
                    <p class="text-rpgate-accent text-xs font-bold uppercase tracking-widest mt-1">Lvl 14 Wizard</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-rpgate-text/60">
                    <div class="bg-rpgate-input-bg p-4 rounded-xl border border-rpgate-card-border">
                        <span class="text-[10px] uppercase tracking-widest text-rpgate-text/30 block mb-1">Email Address</span>
                        <span class="font-medium text-rpgate-text">{{ authStore.user?.email || 'wizard@rpgate.com' }}</span>
                    </div>
                    <div class="bg-rpgate-input-bg p-4 rounded-xl border border-rpgate-card-border">
                        <span class="text-[10px] uppercase tracking-widest text-rpgate-text/30 block mb-1">Username</span>
                        <span class="font-medium text-rpgate-text">{{ authStore.user?.username || 'alaric_wizard' }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
