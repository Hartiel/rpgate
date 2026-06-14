<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import { ArrowRight } from '@lucide/vue';
import IconGoogle from '@/Components/Icons/IconGoogle.vue';
import IconGithub from '@/Components/Icons/IconGithub.vue';

const authStore = useAuthStore();
const router = useRouter();

const form = ref({
    email: '',
    password: '',
    remember: false
});

const handleLogin = async () => {
    const success = await authStore.login(form.value);
    if (success) await router.push({ name: 'home' });
};

const clearErrors = () => {
    authStore.errors = {};
    authStore.globalError = null;
};
</script>

<template>
    <div class="space-y-6">
        <div>
            <h1 class="font-serif text-3xl font-bold text-rpgate-header">Welcome back</h1>
            <p class="mt-2 text-sm text-rpgate-accent/70">Enter your credentials to re-enter the realm.</p>
        </div>

        <div
            v-if="authStore.globalError"
            class="rounded border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-400/90 transition-all duration-300"
        >
            <div class="flex items-center gap-2">
                <span>⚠️ {{ authStore.globalError }}</span>
            </div>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
            <div>
                <label class="block mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                    Email Address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="rpg-input"
                    :class="(authStore.errors.email || authStore.errors.password || authStore.globalError) ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                    placeholder="alchemist@rpgate.com"
                    required
                    @input="clearErrors"
                />
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                        Password
                    </label>
                    <router-link to="#" class="text-[10px] font-bold uppercase tracking-widest text-rpgate-cta transition hover:text-rpgate-accent">
                        Lost the key?
                    </router-link>
                </div>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="rpg-input"
                    :class="(authStore.errors.email || authStore.errors.password || authStore.globalError) ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                    placeholder="••••••••••••"
                    required
                    @input="clearErrors"
                />
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" v-model="form.remember" id="remember" class="accent-rpgate-cta">
                <label for="remember" class="text-xs text-rpgate-text/60">Remember my soul</label>
            </div>

            <button
                type="submit"
                class="group rpg-btn-primary"
            >
                <span class="relative z-10 flex items-center justify-center gap-2">
                    BEGIN ADVENTURE <ArrowRight :size="18"></ArrowRight>
                </span>
            </button>
        </form>

        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/10"></div></div>
            <div class="relative flex justify-center text-[10px] font-bold uppercase tracking-widest">
                <span class="bg-rpgate-bg px-4 text-white/30">Or continue with</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <button class="group flex items-center justify-center gap-3 rounded border border-white/10 py-3 text-[10px] font-bold uppercase tracking-widest text-rpgate-header hover:border-rpgate-accent transition-colors">
                <IconGoogle class="h-4 w-4 grayscale opacity-70 transition-all duration-300 group-hover:grayscale-0 group-hover:opacity-100" />
                Google
            </button>

            <button class="group flex items-center justify-center gap-3 rounded border border-white/10 py-3 text-[10px] font-bold uppercase tracking-widest text-rpgate-header hover:border-rpgate-accent transition-colors">
                <IconGithub class="h-5 w-5 opacity-70 transition-all duration-300 group-hover:opacity-100 group-hover:text-rpgate-accent" />
                GitHub
            </button>
        </div>

        <p class="text-center text-xs text-rpgate-text/40">
            New to the forge?
            <router-link :to="{ name: 'register' }" class="font-bold text-rpgate-accent transition hover:text-rpgate-cta">Create your identity</router-link>
        </p>
    </div>
</template>
