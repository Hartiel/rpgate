<script setup lang="ts">
import { ref } from 'vue';

const searchQuery = ref('');

const popularRooms = [
    {
        id: 1,
        title: 'The Whispering Wo...',
        players: '4/6',
        image: 'https://images.unsplash.com/photo-1511447333015-45b65e60f6d5?q=80&w=2000&auto=format&fit=crop',
        tags: ['BEGINNER FRIENDLY', 'ROLEPLAY']
    },
    {
        id: 2,
        title: 'Sands of Aka tosh',
        players: '5/6',
        image: 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?q=80&w=2000&auto=format&fit=crop',
        tags: ['HARDCORE', 'TACTICAL']
    },
    {
        id: 3,
        title: 'Voyage of the Damn...',
        players: '2/4',
        image: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=2000&auto=format&fit=crop',
        tags: ['NARRATIVE', 'MYSTERY']
    }
];
</script>

<template>
    <div class="p-12 max-w-7xl mx-auto space-y-12 pb-24">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="space-y-4">
                <h1 class="text-6xl font-serif text-rpgate-header italic tracking-tight">The Grand Lobby</h1>
                <p class="text-rpgate-text/40 max-w-2xl text-lg leading-relaxed">
                    Your gateway to infinite realms. Seek a fellowship or manifest your own destiny in the halls of QuestForge.
                </p>
            </div>
        </header>

        <!-- Search Bar -->
        <div class="relative group max-w-3xl">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-rpgate-text/20 group-focus-within:text-rpgate-accent transition-colors"></i>
            <input
                v-model="searchQuery"
                type="text"
                placeholder="FIND A REALM BY NAME OR CAMPAIGN..."
                class="w-full bg-rpgate-card-bg border border-rpgate-card-border rounded-xl py-5 pl-14 pr-6 text-sm tracking-widest uppercase font-bold text-rpgate-text placeholder:text-rpgate-text/20 focus:outline-none focus:border-rpgate-accent/50 focus:ring-1 focus:ring-rpgate-accent/50 transition-all shadow-inner"
            >
        </div>

        <!-- Featured Section -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[10px] uppercase tracking-[0.3em] font-bold text-rpgate-cta">Current Activity</h2>
                    <h3 class="text-3xl font-serif font-bold text-rpgate-header mt-1">Online Campaign Rooms</h3>
                </div>
                <button class="text-xs font-bold text-rpgate-accent uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
                    View all realms <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Featured Card -->
                <div class="lg:col-span-2 relative group overflow-hidden rounded-2xl aspect-21/9 border border-rpgate-card-border">
                    <img
                        src="https://images.unsplash.com/photo-1514539079130-25950c84af65?q=80&w=2000&auto=format&fit=crop"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-60"
                    >
                    <div class="absolute inset-0 bg-linear-to-t from-black via-black/40 to-transparent"></div>

                    <div class="absolute inset-0 p-10 flex flex-col justify-end">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-rpgate-cta animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-rpgate-cta">Live Session</span>
                        </div>
                        <h4 class="text-5xl font-serif font-bold text-white mb-4">The Obsidian Throne</h4>
                        <p class="text-white/60 max-w-md text-sm leading-relaxed mb-8">
                            Descent into the core of the volcanic peaks where the ancient king awaits his challengers.
                        </p>
                        <div class="flex items-center gap-6">
                            <button class="px-8 py-3 bg-rpgate-cta text-black text-xs font-bold uppercase tracking-widest rounded shadow-[0_0_20px_rgba(230,137,26,0.4)] hover:shadow-[0_0_30px_rgba(230,137,26,0.6)] transition-all">
                                Join Room
                            </button>
                            <div class="flex -space-x-2">
                                <img src="https://i.pravatar.cc/100?u=1" class="w-8 h-8 rounded-full border-2 border-black object-cover">
                                <img src="https://i.pravatar.cc/100?u=2" class="w-8 h-8 rounded-full border-2 border-black object-cover">
                                <div class="w-8 h-8 rounded-full border-2 border-black bg-rpgate-input-bg flex items-center justify-center text-[10px] font-bold text-rpgate-text/40">
                                    +3
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lobby Vitals -->
                <div class="rpg-card p-8 flex flex-col">
                    <div class="w-10 h-10 rounded-lg bg-rpgate-cta/10 flex items-center justify-center mb-8 border border-rpgate-cta/10">
                        <i class="fa-solid fa-chart-line text-rpgate-cta text-lg"></i>
                    </div>
                    <h4 class="text-2xl font-serif font-bold text-rpgate-header mb-2">Lobby Vitals</h4>
                    <p class="text-rpgate-text/40 text-xs leading-relaxed mb-8">Current activity across all active planes.</p>

                    <div class="space-y-6 flex-1">
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs font-medium text-rpgate-text/60">Active Players</span>
                                <span class="text-xl font-bold text-rpgate-cta tracking-tight">1,204</span>
                            </div>
                            <div class="h-1.5 w-full bg-rpgate-card-bg rounded-full overflow-hidden">
                                <div class="h-full bg-rpgate-cta rounded-full w-4/5 shadow-[0_0_10px_rgba(230,137,26,0.5)]"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-4 border-y border-rpgate-card-border">
                            <span class="text-xs font-medium text-rpgate-text/60">Public Realms</span>
                            <span class="text-xl font-bold text-rpgate-cta tracking-tight">42</span>
                        </div>
                    </div>

                    <button class="mt-8 w-full py-4 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-text/40 hover:text-rpgate-header hover:bg-rpgate-card-bg rounded-xl border border-rpgate-card-border transition-all">
                        Refresh List
                    </button>
                </div>
            </div>
        </section>

        <!-- Popular Rooms -->
        <section class="space-y-6">
            <header>
                <h2 class="text-[10px] uppercase tracking-[0.3em] font-bold text-rpgate-accent">Top Picks</h2>
                <h3 class="text-3xl font-serif font-bold text-rpgate-header mt-1">Popular Rooms</h3>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Room Cards -->
                <div v-for="room in popularRooms" :key="room.id" class="group rpg-card overflow-hidden flex flex-col hover:border-rpgate-accent/30 hover:-translate-y-1">
                    <div class="relative aspect-square">
                        <img :src="room.image" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                        <div class="absolute inset-0 bg-linear-to-t from-rpgate-bg via-transparent to-transparent"></div>
                        <div class="absolute top-4 right-4 px-2 py-1 bg-rpgate-bg/60 backdrop-blur-md rounded border border-rpgate-card-border flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-[10px] text-rpgate-accent"></i>
                            <span class="text-[10px] font-bold text-rpgate-text">{{ room.players }}</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <h5 class="text-lg font-serif font-bold text-rpgate-header truncate">{{ room.title }}</h5>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="tag in room.tags" :key="tag" class="px-2 py-1 bg-rpgate-card-bg rounded text-[8px] font-bold tracking-widest text-rpgate-text/40 border border-rpgate-card-border">
                                {{ tag }}
                            </span>
                        </div>
                        <button class="w-full py-3 bg-rpgate-card-bg border border-rpgate-card-border rounded text-[10px] font-bold uppercase tracking-widest text-rpgate-text/60 hover:text-rpgate-header hover:bg-rpgate-card-bg/80 transition-all">
                            Request Entry
                        </button>
                    </div>
                </div>

                <!-- Manifest Realm Card -->
                <button class="group border-2 border-dashed border-rpgate-card-border rounded-xl flex flex-col items-center justify-center gap-4 hover:border-rpgate-cta/40 hover:bg-rpgate-cta/2 transition-all min-h-100">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full border border-rpgate-card-border flex items-center justify-center group-hover:border-rpgate-cta/40 transition-colors">
                            <i class="fa-solid fa-plus text-rpgate-text/20 group-hover:text-rpgate-cta transition-colors"></i>
                        </div>
                        <div class="absolute -top-12 -right-12 w-12 h-12 rounded-xl bg-rpgate-cta shadow-[0_0_20px_rgba(230,137,26,0.4)] flex items-center justify-center scale-0 group-hover:scale-100 transition-transform duration-500 rotate-12">
                            <i class="fa-solid fa-wand-magic-sparkles text-black"></i>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-rpgate-text/20 group-hover:text-rpgate-header transition-colors">Manifest Realm</span>
                </button>
            </div>
        </section>
    </div>
</template>

<style scoped>
</style>
