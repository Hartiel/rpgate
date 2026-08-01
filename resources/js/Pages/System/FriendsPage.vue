<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useFriendStore } from '@/stores/friendStore';
import { useRouter } from 'vue-router';
import { 
    UsersIcon, 
    InboxIcon, 
    SearchIcon, 
    UserPlusIcon, 
    UserMinusIcon,
    CheckIcon, 
    XIcon,
    ExternalLinkIcon,
    SparklesIcon,
    AlertCircleIcon
} from '@lucide/vue';
import axios from 'axios';

const friendStore = useFriendStore();
const router = useRouter();

const activeTab = ref<'friends' | 'pending' | 'discover'>('friends');
const localSearchQuery = ref('');
const discoverSearchQuery = ref('');
const discoverResults = ref<any[]>([]);
const isDiscoverSearching = ref(false);

onMounted(() => {
    friendStore.fetchFriends();
    friendStore.fetchPending();
});

// Filter friends locally
const filteredFriends = computed(() => {
    const query = localSearchQuery.value.toLowerCase().trim();
    if (!query) return friendStore.friends;
    return friendStore.friends.filter(friend => 
        friend.name.toLowerCase().includes(query) || 
        friend.username.toLowerCase().includes(query)
    );
});

// Search users in database (Discover tab)
const handleDiscoverSearch = async () => {
    const query = discoverSearchQuery.value.trim();
    if (!query) {
        discoverResults.value = [];
        return;
    }

    isDiscoverSearching.value = true;
    try {
        const response = await axios.get(`/api/users`, { params: { search: query } });
        discoverResults.value = response.data.data;
    } catch (err) {
        console.error('Error searching users:', err);
    } finally {
        isDiscoverSearching.value = false;
    }
};

// Actions inside lists
const acceptRequest = async (userId: string) => {
    await friendStore.acceptFriendRequest(userId);
};

const declineRequest = async (userId: string) => {
    await friendStore.declineFriendRequest(userId);
};

const cancelRequest = async (userId: string) => {
    await friendStore.declineFriendRequest(userId);
};

const sendRequest = async (userId: string) => {
    const ok = await friendStore.sendFriendRequest(userId);
    if (ok) {
        await handleDiscoverSearch();
    }
};

const viewProfile = (userId: string) => {
    router.push({ name: 'profile', params: { id: userId } });
};
</script>

<template>
    <div class="p-12 max-w-7xl mx-auto space-y-12 pb-24">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="space-y-4">
                <h1 class="text-6xl font-serif text-rpgate-header italic tracking-tight">The Social Guild</h1>
                <p class="text-rpgate-text/40 max-w-2xl text-lg leading-relaxed">
                    Build your fellowship, organize campaigns, and connect with other adventurers across the realms.
                </p>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-rpgate-card-border gap-6">
            <button 
                @click="activeTab = 'friends'"
                class="pb-4 text-sm font-bold uppercase tracking-widest flex items-center gap-2 transition-all relative"
                :class="activeTab === 'friends' ? 'text-rpgate-cta' : 'text-rpgate-text/40 hover:text-rpgate-header'"
            >
                <UsersIcon :size="16" />
                Guild Members ({{ friendStore.friends.length }})
                <span v-if="activeTab === 'friends'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-rpgate-cta"></span>
            </button>

            <button 
                @click="activeTab = 'pending'"
                class="pb-4 text-sm font-bold uppercase tracking-widest flex items-center gap-2 transition-all relative"
                :class="activeTab === 'pending' ? 'text-rpgate-cta' : 'text-rpgate-text/40 hover:text-rpgate-header'"
            >
                <InboxIcon :size="16" />
                Pending Requests
                <span v-if="friendStore.pendingReceived.length > 0" class="px-2 py-0.5 text-[10px] bg-rpgate-cta text-black rounded-full font-bold">
                    {{ friendStore.pendingReceived.length }}
                </span>
                <span v-if="activeTab === 'pending'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-rpgate-cta"></span>
            </button>

            <button 
                @click="activeTab = 'discover'"
                class="pb-4 text-sm font-bold uppercase tracking-widest flex items-center gap-2 transition-all relative"
                :class="activeTab === 'discover' ? 'text-rpgate-cta' : 'text-rpgate-text/40 hover:text-rpgate-header'"
            >
                <SparklesIcon :size="16" />
                Discover Players
                <span v-if="activeTab === 'discover'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-rpgate-cta"></span>
            </button>
        </div>

        <!-- Global Error Notification -->
        <div v-if="friendStore.error" class="bg-red-950/20 border border-red-800/40 p-4 rounded-xl flex items-center gap-3 text-red-300 text-sm">
            <AlertCircleIcon :size="18" />
            <span>{{ friendStore.error }}</span>
        </div>

        <!-- Tab contents -->
        <!-- TAB 1: Guild Members (Friends) -->
        <div v-if="activeTab === 'friends'" class="space-y-6">
            <!-- Local Search Bar -->
            <div class="relative group max-w-xl">
                <SearchIcon class="absolute left-4 top-1/2 -translate-y-1/2 text-rpgate-text/20 group-focus-within:text-rpgate-cta transition-colors" :size="18" />
                <input
                    v-model="localSearchQuery"
                    type="text"
                    placeholder="Search guild members..."
                    class="w-full bg-rpgate-card-bg border border-rpgate-card-border rounded-xl py-4 pl-12 pr-6 text-sm text-rpgate-text placeholder:text-rpgate-text/20 focus:outline-none focus:border-rpgate-cta/30 transition-all shadow-inner"
                >
            </div>

            <!-- Loader -->
            <div v-if="friendStore.isLoading && friendStore.friends.length === 0" class="flex flex-col items-center justify-center p-12 text-rpgate-text/20">
                <div class="w-8 h-8 border-2 border-rpgate-cta border-t-transparent rounded-full animate-spin mb-4"></div>
                <span class="text-xs uppercase tracking-widest font-bold">Querying the registry...</span>
            </div>

            <!-- Friends Grid -->
            <div v-else-if="filteredFriends.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="friend in filteredFriends" 
                    :key="friend.id"
                    class="rpg-card p-6 flex items-center gap-4 hover:border-rpgate-cta/30 transition-all group"
                >
                    <div @click="viewProfile(friend.id)" class="w-14 h-14 rounded-xl overflow-hidden bg-rpgate-input-bg border border-rpgate-card-border cursor-pointer relative">
                        <img :src="friend.avatar" class="w-full h-full object-cover transition-transform group-hover:scale-110" />
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h4 
                            @click="viewProfile(friend.id)" 
                            class="font-serif font-bold text-rpgate-header hover:text-rpgate-cta cursor-pointer truncate transition-colors"
                        >
                            {{ friend.name }}
                        </h4>
                        <p class="text-[10px] text-rpgate-text/40 uppercase tracking-widest">
                            @{{ friend.username }}#{{ friend.discriminator }}
                        </p>
                    </div>

                    <button 
                        @click="viewProfile(friend.id)"
                        class="p-2.5 bg-rpgate-card-bg border border-rpgate-card-border hover:bg-rpgate-cta hover:text-black rounded-lg transition-all"
                        title="View profile"
                    >
                        <ExternalLinkIcon :size="14" />
                    </button>
                </div>
            </div>

            <div v-else class="text-center py-20 bg-rpgate-card-bg/20 border border-dashed border-rpgate-card-border rounded-2xl">
                <UsersIcon :size="48" class="mx-auto text-rpgate-text/10 mb-4" />
                <h3 class="text-lg font-serif font-bold text-rpgate-header mb-1">No Fellow Adventurers</h3>
                <p class="text-sm text-rpgate-text/30 max-w-sm mx-auto">
                    {{ localSearchQuery ? 'No members match your search criteria.' : 'Your guild roster is currently empty. Explore the realm to find allies.' }}
                </p>
            </div>
        </div>

        <!-- TAB 2: Pending Invites -->
        <div v-if="activeTab === 'pending'" class="space-y-10">
            <!-- Incoming Invites -->
            <div class="space-y-6">
                <h3 class="text-xl font-serif font-bold text-rpgate-header flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-rpgate-cta"></span>
                    Incoming Requests ({{ friendStore.pendingReceived.length }})
                </h3>

                <div v-if="friendStore.pendingReceived.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="invite in friendStore.pendingReceived" 
                        :key="invite.id"
                        class="rpg-card p-6 flex items-center justify-between gap-4 border-l-2 border-l-rpgate-cta"
                    >
                        <div class="flex items-center gap-4 min-w-0">
                            <div @click="viewProfile(invite.id)" class="w-12 h-12 rounded-lg overflow-hidden bg-rpgate-input-bg border border-rpgate-card-border cursor-pointer">
                                <img :src="invite.avatar" class="w-full h-full object-cover" />
                            </div>
                            <div class="min-w-0">
                                <h4 @click="viewProfile(invite.id)" class="font-serif font-bold text-rpgate-header hover:text-rpgate-cta cursor-pointer truncate transition-colors">
                                    {{ invite.name }}
                                </h4>
                                <p class="text-[9px] text-rpgate-text/40 uppercase tracking-widest">
                                    @{{ invite.username }}#{{ invite.discriminator }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button 
                                @click="acceptRequest(invite.id)" 
                                class="p-2 bg-rpgate-cta text-black rounded hover:opacity-90 transition-all"
                                title="Accept request"
                            >
                                <CheckIcon :size="14" />
                            </button>
                            <button 
                                @click="declineRequest(invite.id)" 
                                class="p-2 bg-rpgate-card-bg border border-rpgate-card-border hover:bg-red-950/20 hover:text-red-400 rounded transition-all"
                                title="Decline request"
                            >
                                <XIcon :size="14" />
                            </button>
                        </div>
                    </div>
                </div>

                <p v-else class="text-sm text-rpgate-text/30 italic">No incoming requests waiting for your sign-off.</p>
            </div>

            <!-- Outgoing Invites -->
            <div class="space-y-6">
                <h3 class="text-xl font-serif font-bold text-rpgate-header flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-rpgate-text/30"></span>
                    Sent Invitations ({{ friendStore.pendingSent.length }})
                </h3>

                <div v-if="friendStore.pendingSent.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="invite in friendStore.pendingSent" 
                        :key="invite.id"
                        class="rpg-card p-6 flex items-center justify-between gap-4"
                    >
                        <div class="flex items-center gap-4 min-w-0">
                            <div @click="viewProfile(invite.id)" class="w-12 h-12 rounded-lg overflow-hidden bg-rpgate-input-bg border border-rpgate-card-border cursor-pointer">
                                <img :src="invite.avatar" class="w-full h-full object-cover" />
                            </div>
                            <div class="min-w-0">
                                <h4 @click="viewProfile(invite.id)" class="font-serif font-bold text-rpgate-header hover:text-rpgate-cta cursor-pointer truncate transition-colors">
                                    {{ invite.name }}
                                </h4>
                                <p class="text-[9px] text-rpgate-text/40 uppercase tracking-widest">
                                    @{{ invite.username }}#{{ invite.discriminator }}
                                </p>
                            </div>
                        </div>

                        <button 
                            @click="cancelRequest(invite.id)" 
                            class="px-3 py-1.5 bg-rpgate-card-bg border border-rpgate-card-border hover:bg-red-950/20 hover:text-red-400 text-[10px] font-bold uppercase tracking-widest rounded transition-all shrink-0"
                        >
                            Cancel
                        </button>
                    </div>
                </div>

                <p v-else class="text-sm text-rpgate-text/30 italic">You haven't sent any friend invitations recently.</p>
            </div>
        </div>

        <!-- TAB 3: Discover Players -->
        <div v-if="activeTab === 'discover'" class="space-y-6">
            <!-- Search bar -->
            <div class="flex gap-4 max-w-2xl">
                <div class="relative group flex-1">
                    <SearchIcon class="absolute left-4 top-1/2 -translate-y-1/2 text-rpgate-text/20 group-focus-within:text-rpgate-cta transition-colors" :size="18" />
                    <input
                        v-model="discoverSearchQuery"
                        type="text"
                        placeholder="Search by name or username..."
                        @keydown.enter="handleDiscoverSearch"
                        class="w-full bg-rpgate-card-bg border border-rpgate-card-border rounded-xl py-4 pl-12 pr-6 text-sm text-rpgate-text placeholder:text-rpgate-text/20 focus:outline-none focus:border-rpgate-cta/30 transition-all shadow-inner"
                    >
                </div>
                <button 
                    @click="handleDiscoverSearch" 
                    class="px-6 py-4 bg-rpgate-cta text-black text-xs font-bold uppercase tracking-widest rounded-xl hover:opacity-90 transition-all shadow-[0_0_20px_rgba(230,137,26,0.3)]"
                >
                    Search
                </button>
            </div>

            <!-- Loader -->
            <div v-if="isDiscoverSearching" class="flex flex-col items-center justify-center p-12 text-rpgate-text/20">
                <div class="w-8 h-8 border-2 border-rpgate-cta border-t-transparent rounded-full animate-spin mb-4"></div>
                <span class="text-xs uppercase tracking-widest font-bold">Scanning the realm...</span>
            </div>

            <!-- Discover list -->
            <div v-else-if="discoverResults.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="user in discoverResults" 
                    :key="user.id"
                    class="rpg-card p-6 flex items-center justify-between gap-4 hover:border-rpgate-cta/20 transition-all group"
                >
                    <div class="flex items-center gap-4 min-w-0">
                        <div @click="viewProfile(user.id)" class="w-12 h-12 rounded-lg overflow-hidden bg-rpgate-input-bg border border-rpgate-card-border cursor-pointer">
                            <img :src="user.avatar" class="w-full h-full object-cover" />
                        </div>
                        <div class="min-w-0">
                            <h4 @click="viewProfile(user.id)" class="font-serif font-bold text-rpgate-header hover:text-rpgate-cta cursor-pointer truncate transition-colors">
                                {{ user.name }}
                            </h4>
                            <p class="text-[9px] text-rpgate-text/40 uppercase tracking-widest">
                                @{{ user.username }}#{{ user.discriminator }}
                            </p>
                        </div>
                    </div>

                    <!-- Discover action buttons -->
                    <div class="shrink-0">
                        <template v-if="user.friendship">
                            <!-- Show pending sent status -->
                            <span 
                                v-if="user.friendship.status === 'pending' && user.friendship.action_user_id !== user.id" 
                                class="text-[9px] font-bold uppercase tracking-widest text-rpgate-text/30 px-3 py-1.5 bg-rpgate-card-bg border border-rpgate-card-border rounded"
                            >
                                Pending Sent
                            </span>
                            <!-- Show pending received status -->
                            <button 
                                v-else-if="user.friendship.status === 'pending'"
                                @click="acceptRequest(user.id)"
                                class="px-3 py-1.5 bg-rpgate-cta text-black text-[9px] font-bold uppercase tracking-widest rounded hover:opacity-90 transition-all"
                            >
                                Accept
                            </button>
                            <!-- Show already friend status -->
                            <span 
                                v-else-if="user.friendship.status === 'accepted'" 
                                class="text-[9px] font-bold uppercase tracking-widest text-green-400 px-3 py-1.5 bg-green-950/10 border border-green-900/30 rounded"
                            >
                                Member
                            </span>
                            <!-- Show blocked status -->
                            <span 
                                v-else-if="user.friendship.status === 'blocked'" 
                                class="text-[9px] font-bold uppercase tracking-widest text-red-400 px-3 py-1.5 bg-red-950/10 border border-red-900/30 rounded"
                            >
                                Blocked
                            </span>
                        </template>
                        <button 
                            v-else 
                            @click="sendRequest(user.id)"
                            class="px-3 py-1.5 bg-rpgate-cta text-black text-[9px] font-bold uppercase tracking-widest rounded hover:opacity-90 transition-all flex items-center gap-1"
                        >
                            <UserPlusIcon :size="10" /> Invite
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else-if="discoverSearchQuery" class="text-center py-20 bg-rpgate-card-bg/20 border border-dashed border-rpgate-card-border rounded-2xl">
                <SearchIcon :size="48" class="mx-auto text-rpgate-text/10 mb-4" />
                <h3 class="text-lg font-serif font-bold text-rpgate-header mb-1">No Guilds Found</h3>
                <p class="text-sm text-rpgate-text/30 max-w-sm mx-auto">
                    We scanned the register, but no players found matching your query.
                </p>
            </div>
        </div>
    </div>
</template>
