<script setup lang="ts">
import { RouterLink } from 'vue-router';
import AppLogo from '@/Components/AppLogo.vue';
import {
    Sparkles,
    CirclePlay,
    Dice5,
    FileSpreadsheet,
    Zap,
    LucideCircleCheck,
    Network,
    Rss,
    SendHorizontal
} from "@lucide/vue";
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
</script>

<template>
    <div class="min-h-screen bg-background text-on-background font-body selection:bg-primary-container selection:text-on-primary-container antialiased relative">
        <!-- TopNavBar (Shared Component Identity) -->
        <nav class="fixed top-0 right-0 left-0 z-50 flex items-center justify-between px-6 bg-stone-900/90 backdrop-blur-md shadow-2xl h-16 border-b border-white/5">
            <div class="flex items-center gap-8">
                <RouterLink :to="{ name: 'welcome' }" class="flex items-center gap-2 group">
                    <AppLogo class="h-7 w-7 text-orange-400 group-hover:rotate-12 transition-transform duration-300" />
                    <span class="text-lg font-serif italic text-orange-400 font-bold tracking-tight">RPGate</span>
                </RouterLink>
                <div class="hidden md:flex gap-6 items-center">
                    <a class="text-stone-400 hover:text-orange-300 transition-colors font-label text-sm tracking-tight" href="#features">Campaigns</a>
                    <a class="text-stone-400 hover:text-orange-300 transition-colors font-label text-sm tracking-tight" href="#features">Features</a>
                    <a class="text-stone-400 hover:text-orange-300 transition-colors font-label text-sm tracking-tight" href="#combat-flow">Combat Timeline</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Auth Store Navigation Actions -->
                <div v-if="!authStore.isAuthenticated" class="flex items-center gap-2">
                    <RouterLink
                        :to="{ name: 'login' }"
                        class="text-orange-400 font-label text-sm font-bold hover:text-orange-300 transition-colors px-4 py-2"
                    >
                        Login
                    </RouterLink>
                    <RouterLink
                        :to="{ name: 'register' }"
                        class="hero-gradient text-on-primary px-5 py-2 rounded-lg font-bold text-sm glow-hover transition-all active:scale-95"
                    >
                        Register
                    </RouterLink>
                </div>
                <div v-else>
                    <RouterLink
                        :to="{ name: 'home' }"
                        class="hero-gradient text-on-primary px-5 py-2 rounded-lg font-bold text-sm glow-hover transition-all active:scale-95"
                    >
                        Enter the Portal
                    </RouterLink>
                </div>
            </div>
        </nav>

        <main class="relative overflow-x-hidden">
            <!-- Hero Section -->
            <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16">
                <div class="absolute inset-0 z-0">
                    <img
                        class="w-full h-full object-cover opacity-40 grayscale-[0.3]"
                        alt="Cinematic atmospheric dark room with glowing amber embers, ancient leather-bound scrolls on a stone table, and sharp dramatic lighting"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1cTXYY1uFphInmYwXI39B3UWyIsKDZt1oUGmWFzGobkjNMcyBKzTxruivBjEHeuX8xOkXUoKUNPW4x-SRT4LpOnKS5cV6v54-Tbo3zhCgb9HM8c9DQDmGJLCK0UrUZRR1q68yd9fvwyqDChx7Wpd3JTe6lscuFWmLwICpPGpGRv2K6xu_FCJS-vJ2JorcZu7AUjYeGaQxYAwoNfPAEAWfcIVi9kvyJGPR7OK19FR4Y-FJlnvoZN4UVKnKthIihB1yhfQfVX3a"
                    />
                    <div class="absolute inset-0 bg-linear-to-t from-background via-transparent to-background/80"></div>
                    <div class="absolute inset-0 bg-linear-to-r from-background via-transparent to-background"></div>
                </div>
                <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
                    <div class="mb-6 flex justify-center">
                        <span class="px-4 py-1 border border-primary/20 rounded-full text-primary font-label text-xs tracking-widest uppercase bg-primary/5">The Digital Alchemist's Toolset</span>
                    </div>
                    <h1 class="font-headline italic text-6xl md:text-8xl lg:text-9xl mb-8 leading-[0.9] tracking-tighter">
                        Forge Your <br/>
                        <span class="text-primary italic">Legend.</span>
                    </h1>
                    <p class="max-w-2xl mx-auto text-lg md:text-xl text-on-surface-variant mb-10 font-body font-light leading-relaxed">
                        A premium digital artifact designed for the elite storyteller. Elevate your tabletop experience with atmospheric rooms and precision tools.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                        <RouterLink
                            :to="authStore.isAuthenticated ? { name: 'home' } : { name: 'register' }"
                            class="hero-gradient text-on-primary px-10 py-4 rounded-xl font-bold text-lg glow-hover transition-all active:scale-98 flex items-center gap-3 cursor-pointer shadow-lg shadow-primary/10"
                        >
                            {{ authStore.isAuthenticated ? 'Enter the Portal' : 'Begin the Ritual' }}
                            <Sparkles></Sparkles>
                        </RouterLink>
                        <a
                            href="#features"
                            class="text-on-surface hover:text-primary transition-colors flex items-center gap-2 group cursor-pointer"
                        >
                            <CirclePlay></CirclePlay>
                            View the Grimoire
                        </a>
                    </div>
                </div>
            </section>

            <!-- Feature Bento Grid -->
            <section id="features" class="py-32 px-6 max-w-7xl mx-auto scroll-mt-20">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Immersive Rooms (The Anchor) -->
                    <div class="md:col-span-8 group relative overflow-hidden rounded-3xl bg-surface-container-low min-h-112.5 border border-white/5 shadow-xl">
                        <div class="absolute inset-0">
                            <img
                                class="w-full h-full object-cover opacity-50 transition-transform duration-700 group-hover:scale-105"
                                alt="Interior of a dark medieval tavern at night with warm hearth fire, soft dust motes in the air, cinematic composition"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo9LRFlUJEw2LMMR9xITCq19dtIbV7bPzr4vlp-6md8G4rkLq_ozOJ1kY5Sac7Swc67aLZYSlJquskkAnu5e4_R1jcIp3qjcdOcQBPYO5SIEez3gzXPMbntkOqKwOqgziEpGyKVewiVbvYvZ58_ASwhzgH8OMe47abe-4TuNOJ07s7I9rGsAaoZrL_yqc9HAFDvhPH8D3JKmjZZ4ENArppQqxsarz0C8pp7xTK_aYbryxXoalFqlBsqFQbPdPeSkubETuZkWuG"
                            />
                            <div class="absolute inset-0 bg-linear-to-t from-surface-container-low via-surface-container-low/20 to-transparent"></div>
                        </div>
                        <div class="relative h-full flex flex-col justify-end p-10 z-10">
                            <span class="text-primary font-label text-xs tracking-[0.2em] uppercase mb-3 font-semibold">Presence</span>
                            <h2 class="font-headline text-5xl mb-4 italic">Immersive Rooms</h2>
                            <p class="text-on-surface-variant max-w-md font-body text-sm md:text-base">Synchronized audio, dynamic lighting, and integrated lore panels create a shared reality for your party.</p>
                        </div>
                    </div>

                    <!-- Real-time Dice -->
                    <div class="md:col-span-4 group bg-surface-container p-10 rounded-3xl flex flex-col justify-between hover:bg-surface-container-high border border-white/5 transition-all shadow-xl hover:-translate-y-1 duration-300">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-10 group-hover:rotate-12 transition-transform border border-primary/20">
                            <Dice5 :size=32 :strokeWidth="2.5" class="text-primary-container"></Dice5>
                        </div>
                        <div>
                            <h2 class="font-headline text-4xl mb-4 italic">Real-time Dice Rolling</h2>
                            <p class="text-on-surface-variant font-body text-sm leading-relaxed">Physics-based 3D rollers with custom arcane particles. Every crit felt by the whole table.</p>
                        </div>
                    </div>

                    <!-- Dynamic Sheets -->
                    <div class="md:col-span-4 group bg-surface-container p-10 rounded-3xl flex flex-col justify-between hover:bg-surface-container-high border border-white/5 transition-all shadow-xl hover:-translate-y-1 duration-300">
                        <div class="w-16 h-16 bg-tertiary/10 rounded-2xl flex items-center justify-center mb-10 group-hover:-translate-y-1 transition-transform border border-tertiary/20">
                            <FileSpreadsheet class="text-tertiary"></FileSpreadsheet>
                        </div>
                        <div>
                            <h2 class="font-headline text-4xl mb-4 italic">Dynamic Sheets</h2>
                            <p class="text-on-surface-variant font-body text-sm leading-relaxed">Automated math, spell-slot tracking, and inventory management that actually works.</p>
                        </div>
                    </div>

                    <!-- Editorial Quote / Aesthetic Filler -->
                    <div class="md:col-span-8 bg-surface-container-lowest border border-white/5 rounded-3xl p-12 flex items-center relative overflow-hidden shadow-xl">
                        <div class="relative z-10">
                            <span class="text-3xl md:text-4xl font-headline italic text-on-surface leading-tight block">
                                "The distinction between a game and an epic lies in the <span class="text-primary">atmosphere</span> of the interface."
                            </span>
                            <div class="mt-6 flex items-center gap-4">
                                <div class="w-10 h-px bg-outline"></div>
                                <span class="font-label text-xs tracking-widest uppercase text-outline">Lord Arcanist, Game Architect</span>
                            </div>
                        </div>
                        <span class="absolute -bottom-10 -right-10 material-symbols-outlined text-[200px] text-white/5 rotate-12 select-none pointer-events-none">history_edu</span>
                    </div>
                </div>
            </section>

            <!-- Deep Feature Detail (Asymmetric Layout) -->
            <section id="combat-flow" class="py-32 bg-surface-container-low/50 border-y border-white/5 relative">
                <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="relative">
                        <div class="aspect-square glass-panel rounded-3xl p-2 relative overflow-hidden border border-white/10 shadow-2xl">
                            <img
                                class="w-full h-full object-cover rounded-2xl opacity-80"
                                alt="Modern high-tech software interface showing RPG character stats with glowing orange progress bars on a dark obsidian background"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1-QmJbU_iYZ-qGzI219jc80wjsDNf3fU57xLpYfRYbJy31FG3_hENDITei_iFPz3vtGLG969vvmQXADpmw2hPsYhOLMxd4XmJc9nIQmBPHEgQSVQidhLseJJ6gzytj1sfBz7VeE9yOwvQ_8Y7QM-JP0NGqV58ulUkUhe_u_rX-5ZCIENbfMRHjXWLa5AzoO8Nu_Y4ml5fxaS7yVcAuxqaEdvIVE24pGXz94lpi2P3HHaOv3YYl_lZyTTp_S5uILwskTZ2TSFl"
                            />
                            <div class="absolute inset-0 bg-linear-to-tr from-primary/20 via-transparent to-transparent pointer-events-none"></div>
                        </div>

                        <!-- Floating Stat Badge (Glassmorphism) -->
                        <div class="absolute -bottom-6 -right-6 glass-panel p-6 rounded-2xl border border-white/10 shadow-2xl backdrop-blur-xl">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-tertiary-container/20 rounded-full flex items-center justify-center border border-tertiary/20">
                                    <Zap></Zap>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-semibold">Mana Regeneration</p>
                                    <p class="text-xl font-bold font-headline text-on-surface">+14.2% / Turn</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <h3 class="font-headline text-5xl italic leading-tight">Master the <span class="text-primary">Alchemical</span> Flow of Combat.</h3>
                        <p class="text-on-surface-variant text-lg leading-relaxed">
                            Our combat tracker isn't just a list; it's a living timeline. Preview upcoming turns, track status effects with visual timers, and trigger environmental hazards with a single tap.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3">
                                <LucideCircleCheck class="text-primary"></LucideCircleCheck>
                                <span class="font-label text-sm md:text-base">Automated Initiative Nesting</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <LucideCircleCheck class="text-primary"></LucideCircleCheck>
                                <span class="font-label text-sm md:text-base">Smart Target-to-Roll Logic</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <LucideCircleCheck class="text-primary"></LucideCircleCheck>
                                <span class="font-label text-sm md:text-base">Integrated Particle FX for Hits & Crits</span>
                            </li>
                        </ul>
                        <div class="pt-6">
                            <a
                                href="#features"
                                class="px-8 py-3 border border-outline-variant hover:border-primary transition-all text-on-surface hover:text-primary rounded-lg font-bold inline-block cursor-pointer"
                            >
                                Explore the Engine
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-40 px-6 text-center relative">
                <div class="max-w-4xl mx-auto space-y-12 relative z-10">
                    <h2 class="font-headline text-5xl md:text-6xl lg:text-7xl italic leading-tight">Your Campaign Deserves <br/> a <span class="text-primary">Better Canvas.</span></h2>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <RouterLink
                            :to="authStore.isAuthenticated ? { name: 'home' } : { name: 'register' }"
                            class="hero-gradient text-on-primary px-12 py-5 rounded-xl font-extrabold text-xl glow-hover transition-all active:scale-95 shadow-2xl shadow-primary/25 cursor-pointer inline-block"
                        >
                            {{ authStore.isAuthenticated ? 'Enter the Portal' : 'Claim Your Realm' }}
                        </RouterLink>
                    </div>
                    <p class="text-stone-500 font-label text-xs tracking-widest uppercase select-none">Free to start. Eternal to master.</p>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-surface-container-lowest py-20 px-6 border-t border-white/5 relative z-10">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <RouterLink :to="{ name: 'welcome' }" class="flex items-center gap-2 mb-6 group">
                        <AppLogo class="h-7 w-7 text-orange-500 group-hover:rotate-12 transition-transform duration-300" />
                        <span class="text-2xl font-serif italic text-orange-500 font-bold tracking-tight">RPGate</span>
                    </RouterLink>
                    <p class="text-on-surface-variant font-body text-sm max-w-xs mb-8">
                        Crafting digital tools for modern adventurers. Experience the next evolution of tabletop storytelling.
                    </p>
                    <div class="flex gap-4">
                        <a class="w-10 h-10 rounded-full border border-outline-variant/20 flex items-center justify-center text-stone-400 hover:text-primary hover:border-primary transition-colors" href="#">
                            <Network></Network>
                        </a>
                        <a class="w-10 h-10 rounded-full border border-outline-variant/20 flex items-center justify-center text-stone-400 hover:text-primary hover:border-primary transition-colors" href="#">
                            <Rss></Rss>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-label text-xs font-bold uppercase tracking-widest text-on-surface mb-6">Nexus</h4>
                    <ul class="space-y-4 text-sm text-stone-500 font-medium">
                        <li><a class="hover:text-primary transition-colors" href="#">Marketplace</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Compendium</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Guilds</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-label text-xs font-bold uppercase tracking-widest text-on-surface mb-6">Company</h4>
                    <ul class="space-y-4 text-sm text-stone-500 font-medium">
                        <li><a class="hover:text-primary transition-colors" href="#">Manifesto</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">The Forge</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Legal Scrolls</a></li>
                    </ul>
                </div>

                <div class="col-span-1 lg:col-span-2">
                    <div class="bg-surface-container p-6 rounded-2xl border border-outline-variant/30 shadow-xl">
                        <h4 class="font-label text-xs font-bold uppercase tracking-widest text-on-surface mb-4">Summon Updates</h4>
                        <div class="relative flex items-center">
                            <input
                                class="w-full bg-surface-container-highest border border-outline-variant/30 rounded-lg py-3 pl-4 pr-12 text-sm focus:ring-1 focus:ring-primary/50 focus:outline-none placeholder-stone-600 text-on-background"
                                placeholder="Alchemist's Email"
                                type="email"
                            />
                            <button class="absolute right-2 p-1.5 text-primary hover:text-orange-400 transition-colors" aria-label="Subscribe">
                                <SendHorizontal></SendHorizontal>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4 text-[10px] uppercase tracking-widest text-stone-600">
                <span>© 2026 RPGate Inc. All Rights Reserved.</span>
                <div class="flex gap-8">
                    <a class="hover:text-on-surface transition-colors" href="#">Privacy</a>
                    <a class="hover:text-on-surface transition-colors" href="#">Terms</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Inject organic grain filter effect overlaying entire page */
.min-h-screen::before {
    content: '';
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    opacity: 0.02;
    pointer-events: none;
    z-index: 2;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    vertical-align: middle;
}

.glass-panel {
    background: rgba(32, 31, 31, 0.7);
    backdrop-filter: blur(16px);
}

.hero-gradient {
    background: linear-gradient(135deg, #ffb77d 0%, #ff8c00 100%);
}

.glow-hover:hover {
    box-shadow: 0 0 25px rgba(255, 183, 125, 0.25);
}
</style>
