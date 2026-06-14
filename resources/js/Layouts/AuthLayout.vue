<script setup lang="ts">
import AppLogo from '@/Components/AppLogo.vue';
import {ref} from "vue";

const image = ref('https://www.dndbeyond.com/attachments/12/150/solo-adventurer-with-a-dragon.jpg')
</script>

<template>
    <div class="auth-wrapper flex min-h-screen bg-rpgate-bg font-sans text-rpgate-text selection:bg-rpgate-cta/30">

        <div class="relative hidden w-1/2 overflow-hidden lg:block">
            <img
                :src="image"
                class="absolute inset-0 h-full w-full object-cover grayscale-[0.2] brightness-[0.7]"
                alt="Auth visual background"
            />

            <div class="absolute inset-0 bg-linear-to-t from-rpgate-bg via-transparent to-rpgate-bg/40"></div>

            <div class="relative z-10 flex h-full flex-col justify-between p-12">
                <div class="flex items-center gap-3">
                    <AppLogo class="h-10 w-10 text-rpgate-header" />
                    <span class="font-serif text-2xl font-bold tracking-widest text-rpgate-header">RPGate</span>
                </div>

                <div class="max-w-md">
                    <h2 class="font-serif text-5xl font-bold leading-tight text-rpgate-header">
                        Your destiny awaits in the shadows.
                    </h2>
                    <p class="mt-6 font-medium tracking-wide text-rpgate-accent opacity-90">
                        Join the ranks of the Digital Alchemists and weave your epic tale within the Shadow Rift.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col items-center justify-center px-6 lg:w-1/2 lg:px-12">

            <div class="mb-8 flex items-center gap-2 lg:hidden">
                <AppLogo class="h-8 w-8 text-rpgate-accent" />
                <span class="font-serif text-xl font-bold text-rpgate-header">RPGate</span>
            </div>

            <div class="w-full max-w-md">
                <router-view />
            </div>
        </div>
    </div>
</template>

<style scoped>
.auth-wrapper::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    opacity: 0.02;
    pointer-events: none;
    z-index: 50;
}
</style>
