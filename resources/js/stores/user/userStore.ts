import { defineStore } from 'pinia';
import axios from 'axios';
import { useAuthStore } from '@/stores/authStore';

export const useUserStore = defineStore('user', () => {
    const updateSettings = async (settingsPayload: { theme: string; compact_mode: boolean }): Promise<boolean> => {
        try {
            const response = await axios.put('/api/user/settings', settingsPayload);
            
            const authStore = useAuthStore();
            authStore.updateCurrentUser(response.data.data);
            
            return true;
        } catch (error) {
            console.error('Failed to update user settings in the realm server:', error);
            throw error;
        }
    };

    return {
        updateSettings,
    };
});