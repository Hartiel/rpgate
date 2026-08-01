import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export interface UserFriend {
    id: string;
    name: string;
    username: string;
    discriminator: string;
    email: string;
    avatar: string;
    friendship?: {
        id: string;
        status: 'pending' | 'accepted' | 'blocked';
        action_user_id: string;
    };
}

export const useFriendStore = defineStore('friend', () => {
    const friends = ref<UserFriend[]>([]);
    const pendingReceived = ref<UserFriend[]>([]);
    const pendingSent = ref<UserFriend[]>([]);
    const isLoading = ref(false);
    const error = ref<string | null>(null);

    const fetchFriends = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await axios.get('/api/friends');
            friends.value = response.data.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to fetch guild members.';
        } finally {
            isLoading.value = false;
        }
    };

    const fetchPending = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await axios.get('/api/friends/pending');
            pendingReceived.value = response.data.data.received;
            pendingSent.value = response.data.data.sent;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to fetch pending invitations.';
        } finally {
            isLoading.value = false;
        }
    };

    const sendFriendRequest = async (friendId: string): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;
        try {
            await axios.post('/api/friends/request', { friend_id: friendId });
            await fetchPending();
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.errors?.friend_id?.[0] || err.response?.data?.message || 'Failed to send invite.';
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    const acceptFriendRequest = async (userId: string): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;
        try {
            await axios.patch(`/api/friends/${userId}/accept`);
            await fetchPending();
            await fetchFriends();
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to accept invite.';
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    const declineFriendRequest = async (userId: string): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;
        try {
            await axios.delete(`/api/friends/${userId}/decline`);
            await fetchPending();
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to decline invite.';
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    const unfriend = async (userId: string): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;
        try {
            await axios.delete(`/api/friends/${userId}`);
            await fetchFriends();
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to remove friend.';
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    const blockUser = async (userId: string): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;
        try {
            await axios.post(`/api/friends/${userId}/block`);
            await fetchFriends();
            await fetchPending();
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to block user.';
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    const unblockUser = async (userId: string): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;
        try {
            await axios.delete(`/api/friends/${userId}/unblock`);
            await fetchFriends();
            await fetchPending();
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to unblock user.';
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    return {
        friends,
        pendingReceived,
        pendingSent,
        isLoading,
        error,
        fetchFriends,
        fetchPending,
        sendFriendRequest,
        acceptFriendRequest,
        declineFriendRequest,
        unfriend,
        blockUser,
        unblockUser,
    };
});
