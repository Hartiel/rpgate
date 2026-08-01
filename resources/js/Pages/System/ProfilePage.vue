<script setup lang="ts">
import { useAuthStore } from '@/stores/authStore';
import { useFriendStore } from '@/stores/friendStore';
import { useRoute } from 'vue-router';
import { ref, onMounted, watch, computed } from 'vue';
import { UserIcon, CheckIcon, UserPlusIcon, UserMinusIcon, BanIcon, UnlockIcon } from '@lucide/vue';
import axios from 'axios';

const authStore = useAuthStore();
const friendStore = useFriendStore();
const route = useRoute();

const userProfile = ref<any>(null);
const isOwnProfile = computed(() => !route.params.id || route.params.id === authStore.user?.id);
const loading = ref(false);

const loadProfile = async () => {
    if (isOwnProfile.value) {
        userProfile.value = authStore.user;
        return;
    }

    loading.value = true;
    try {
        const response = await axios.get(`/api/users/${route.params.id}`);
        userProfile.value = response.data.data;
    } catch (err) {
        console.error('Failed to load profile:', err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadProfile();
});

watch(() => route.params.id, () => {
    loadProfile();
});

// Relationship state computed values
const friendshipState = computed(() => {
    if (isOwnProfile.value || !userProfile.value) return null;
    return userProfile.value.friendship || null;
});

const isFriend = computed(() => friendshipState.value?.status === 'accepted');
const isPendingReceived = computed(() => 
    friendshipState.value?.status === 'pending' && 
    friendshipState.value?.action_user_id !== authStore.user?.id
);
const isPendingSent = computed(() => 
    friendshipState.value?.status === 'pending' && 
    friendshipState.value?.action_user_id === authStore.user?.id
);
const isBlockedByMe = computed(() => 
    friendshipState.value?.status === 'blocked' && 
    friendshipState.value?.action_user_id === authStore.user?.id
);
const isBlockedByThem = computed(() => 
    friendshipState.value?.status === 'blocked' && 
    friendshipState.value?.action_user_id !== authStore.user?.id
);

// Actions
const handleAddFriend = async () => {
    if (!userProfile.value) return;
    const ok = await friendStore.sendFriendRequest(userProfile.value.id);
    if (ok) await loadProfile();
};

const handleAcceptFriend = async () => {
    if (!userProfile.value) return;
    const ok = await friendStore.acceptFriendRequest(userProfile.value.id);
    if (ok) await loadProfile();
};

const handleDeclineOrCancel = async () => {
    if (!userProfile.value) return;
    const ok = await friendStore.declineFriendRequest(userProfile.value.id);
    if (ok) await loadProfile();
};

const handleUnfriend = async () => {
    if (!userProfile.value) return;
    const ok = await friendStore.unfriend(userProfile.value.id);
    if (ok) await loadProfile();
};

const handleBlock = async () => {
    if (!userProfile.value) return;
    const ok = await friendStore.blockUser(userProfile.value.id);
    if (ok) await loadProfile();
};

const handleUnblock = async () => {
    if (!userProfile.value) return;
    const ok = await friendStore.unblockUser(userProfile.value.id);
    if (ok) await loadProfile();
};
</script>

<template>
    <div class="p-12 max-w-4xl mx-auto space-y-10 pb-24">
        <!-- Header -->
        <header class="space-y-4">
            <h2 class="text-[10px] uppercase tracking-[0.3em] font-bold text-rpgate-cta">
                {{ isOwnProfile ? 'User Identity' : 'Guild Member' }}
            </h2>
            <h1 class="text-5xl font-serif text-rpgate-header italic tracking-tight">
                {{ isOwnProfile ? 'Character Profile' : 'Inspect Profile' }}
            </h1>
            <p class="text-rpgate-text/40 max-w-2xl text-base leading-relaxed">
                {{ isOwnProfile 
                    ? 'Inspect your credentials, credentials within the guild, and your character stats in the realm.' 
                    : 'Inspect their credentials, level, and determine your guild relationship status.'
                }}
            </p>
        </header>

        <!-- Loading state -->
        <div v-if="loading" class="flex flex-col items-center justify-center p-20 space-y-4 bg-rpgate-card-bg border border-rpgate-card-border rounded-2xl">
            <div class="w-10 h-10 border-4 border-rpgate-cta border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs uppercase tracking-widest text-rpgate-text/30 font-bold">Summoning profile...</p>
        </div>

        <!-- Profile Detail Card -->
        <section v-else-if="userProfile" class="rpg-card flex flex-col md:flex-row gap-8 items-center p-8">
            <div class="w-32 h-32 rounded-2xl bg-linear-to-br from-rpgate-accent/20 to-rpgate-cta/20 flex items-center justify-center border border-rpgate-card-border overflow-hidden shadow-2xl relative group">
                <img v-if="userProfile.avatar" :src="userProfile.avatar" class="w-full h-full object-cover" />
                <UserIcon v-else class="w-16 h-16 text-rpgate-text/40" />
            </div>

            <div class="flex-1 space-y-4 text-center md:text-left w-full">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
                    <div>
                        <h3 class="text-2xl font-serif font-bold text-rpgate-header">{{ userProfile.name }}</h3>
                        <p class="text-rpgate-accent text-xs font-bold uppercase tracking-widest mt-1">
                            @{{ userProfile.username }}#{{ userProfile.discriminator }}
                        </p>
                    </div>

                    <!-- Relationship Action Buttons (only for other users) -->
                    <div v-if="!isOwnProfile" class="flex flex-wrap items-center gap-3 justify-center md:justify-end">
                        <template v-if="isBlockedByMe">
                            <button @click="handleUnblock" class="px-4 py-2 bg-green-900/30 border border-green-600/50 hover:bg-green-800/40 text-green-300 text-xs font-bold uppercase tracking-widest rounded flex items-center gap-2 transition-all">
                                <UnlockIcon :size="14" /> Unblock User
                            </button>
                        </template>
                        <template v-else-if="isBlockedByThem">
                            <span class="px-4 py-2 bg-red-950/20 border border-red-900/40 text-red-400/60 text-xs font-bold uppercase tracking-widest rounded flex items-center gap-2 cursor-not-allowed">
                                <BanIcon :size="14" /> Blocked
                            </span>
                        </template>
                        <template v-else>
                            <!-- Friend/Pending buttons -->
                            <button v-if="isFriend" @click="handleUnfriend" class="px-4 py-2 bg-red-950/25 border border-red-850/50 hover:bg-red-900/30 text-red-300 text-xs font-bold uppercase tracking-widest rounded flex items-center gap-2 transition-all">
                                <UserMinusIcon :size="14" /> Remove Friend
                            </button>
                            <div v-else-if="isPendingReceived" class="flex items-center gap-2">
                                <button @click="handleAcceptFriend" class="px-4 py-2 bg-rpgate-cta hover:opacity-90 text-black text-xs font-bold uppercase tracking-widest rounded flex items-center gap-1.5 transition-all shadow-[0_0_15px_rgba(230,137,26,0.4)]">
                                    <CheckIcon :size="14" /> Accept
                                </button>
                                <button @click="handleDeclineOrCancel" class="px-4 py-2 bg-rpgate-card-bg border border-rpgate-card-border hover:text-red-400 text-xs font-bold uppercase tracking-widest rounded transition-all">
                                    Decline
                                </button>
                            </div>
                            <button v-else-if="isPendingSent" @click="handleDeclineOrCancel" class="px-4 py-2 bg-rpgate-card-bg border border-rpgate-card-border hover:bg-red-950/10 hover:text-red-400 text-xs font-bold uppercase tracking-widest rounded flex items-center gap-2 transition-all">
                                Cancel Invite
                            </button>
                            <button v-else @click="handleAddFriend" class="px-4 py-2 bg-rpgate-cta text-black text-xs font-bold uppercase tracking-widest rounded flex items-center gap-2 transition-all shadow-[0_0_20px_rgba(230,137,26,0.3)] hover:shadow-[0_0_30px_rgba(230,137,26,0.5)]">
                                <UserPlusIcon :size="14" /> Add Friend
                            </button>

                            <!-- Block option -->
                            <button @click="handleBlock" class="p-2 bg-rpgate-card-bg border border-rpgate-card-border hover:bg-red-950/20 hover:text-red-400 rounded transition-all" title="Block User">
                                <BanIcon :size="14" />
                            </button>
                        </template>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-rpgate-text/60">
                    <div class="bg-rpgate-input-bg p-4 rounded-xl border border-rpgate-card-border">
                        <span class="text-[10px] uppercase tracking-widest text-rpgate-text/30 block mb-1">Email Address</span>
                        <span class="font-medium text-rpgate-text">{{ userProfile.email }}</span>
                    </div>
                    <div class="bg-rpgate-input-bg p-4 rounded-xl border border-rpgate-card-border">
                        <span class="text-[10px] uppercase tracking-widest text-rpgate-text/30 block mb-1">Username</span>
                        <span class="font-medium text-rpgate-text">{{ userProfile.username }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
