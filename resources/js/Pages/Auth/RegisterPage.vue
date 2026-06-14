<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submitRegistration = async () => {
    const success = await authStore.register(form.value);

    if (success) {
        // Se registrar com sucesso, chuta pra home/dashboard
        await router.push({ name: 'home' });
    }
};
</script>

<template>
    <div class="space-y-6">
        <div>
            <h1 class="font-serif text-3xl font-bold text-rpgate-header">Create your legend</h1>
            <p class="mt-2 text-sm text-rpgate-accent/70">Forge your identity in the Shadow Rift.</p>
        </div>

        <div
            v-if="authStore.globalError"
            class="rounded border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-400/90 transition-all duration-300"
        >
            <div class="flex items-center gap-2">
                <span>⚠️ {{ authStore.globalError }}</span>
            </div>
        </div>

        <form @submit.prevent="submitRegistration" class="space-y-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                        Name
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="rpg-input"
                        :class="authStore.errors.name ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                        placeholder="Name"
                        required
                        @input="delete authStore.errors.name; authStore.globalError = null"
                    >
                    <span v-if="authStore.errors.name" class="block mt-1.5 text-xs text-red-400/90">{{ authStore.errors.name[0] }}</span>
                </div>

                <div>
                    <label for="username" class="block mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                        Username
                    </label>
                    <input
                        id="username"
                        v-model="form.username"
                        type="text"
                        class="rpg-input"
                        :class="authStore.errors.username ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                        placeholder="user_name"
                        required
                        @input="delete authStore.errors.username; authStore.globalError = null"
                    >
                    <span v-if="authStore.errors.username" class="block mt-1.5 text-xs text-red-400/90">{{ authStore.errors.username[0] }}</span>
                </div>
            </div>

            <div>
                <label for="email" class="block mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                    Email Address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="rpg-input"
                    :class="authStore.errors.email ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                    placeholder="username@rpgate.com"
                    required
                    @input="delete authStore.errors.email; authStore.globalError = null"
                >
                <span v-if="authStore.errors.email" class="block mt-1.5 text-xs text-red-400/90">{{ authStore.errors.email[0] }}</span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="block mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="rpg-input"
                        :class="authStore.errors.password ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                        placeholder="••••••••••••"
                        required
                        @input="delete authStore.errors.password; authStore.globalError = null"
                    >
                    <span v-if="authStore.errors.password" class="block mt-1.5 text-xs text-red-400/90">{{ authStore.errors.password[0] }}</span>
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-rpgate-accent/60">
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="rpg-input"
                        :class="authStore.errors.password_confirmation ? '!border-red-500/50 focus:!border-red-500 focus:!ring-red-500' : ''"
                        placeholder="••••••••••••"
                        required
                        @input="delete authStore.errors.password_confirmation; delete authStore.errors.password; authStore.globalError = null"
                    >
                    <span v-if="authStore.errors.password_confirmation" class="block mt-1.5 text-xs text-red-400/90">{{ authStore.errors.password_confirmation[0] }}</span>
                </div>
            </div>

            <button
                type="submit"
                class="group rpg-btn-primary mt-2"
            >
                <span class="relative z-10 flex items-center justify-center gap-2">
                    FORGE IDENTITY <i class="fa-solid fa-arrow-right text-xs"></i>
                </span>
            </button>
        </form>

        <div class="relative py-2">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/10"></div></div>
            <div class="relative flex justify-center text-[10px] font-bold uppercase tracking-widest">
                <span class="bg-rpgate-bg px-4 text-white/30">Already forged?</span>
            </div>
        </div>

        <p class="text-center text-xs text-rpgate-text/40">
            Return to your realm.
            <router-link :to="{ name: 'login' }" class="font-bold text-rpgate-accent transition hover:text-rpgate-cta">Log in</router-link>
        </p>
    </div>
</template>
