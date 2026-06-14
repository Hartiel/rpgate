import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

interface LaravelValidationError {
    message?: string;
    errors?: Record<string, string[]>;
}

export const useAuthStore = defineStore('auth', () => {

    const user = ref<Record<string, any> | null>(null);
    const isAuthenticated = ref(false);
    const isInitialized = ref(false);
    const errors = ref<Record<string, string[]>>({});
    const globalError = ref<string | null>(null);

    const register = async (payload: Record<string, string>): Promise<boolean> => {
        errors.value = {};
        globalError.value = null;

        try {
            await axios.post('/register', payload);
            isAuthenticated.value = true;

            await checkAuth();
            return true;
        } catch (error: unknown) {
            if (axios.isAxiosError<LaravelValidationError>(error)) {
                const status = error.response?.status;
                const data = error.response?.data;

                if (status === 422) {
                    errors.value = data?.errors || {};
                } else {
                    globalError.value = data?.message || 'An error occurred during registration.';
                }
            } else {
                globalError.value = 'Network Error';
            }
            return false;
        }
    };

    const login = async (payload: Record<string, string | boolean>): Promise<boolean> => {
        errors.value = {};
        globalError.value = null;

        try {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/login', payload);
            isAuthenticated.value = true;

            await checkAuth();
            return true;
        } catch (error: unknown) {
            if (axios.isAxiosError<LaravelValidationError>(error)) {
                const status = error.response?.status;
                const data = error.response?.data;

                if (status === 422) {
                    errors.value = data?.errors || {};
                    globalError.value = data?.message || 'Please check the errors below.';
                } else if (status === 401) {
                    globalError.value = data?.message || 'These credentials do not match our records.';
                } else if (status === 429) {
                    globalError.value = 'Too many login attempts. Please slow down, traveler.';
                } else {
                    globalError.value = 'The realm server is unresponsive. Try again later.';
                }
            } else {
                globalError.value = 'Network error. Check your connection to the rift.';
            }
            return false;
        }
    };

    const logout = async (): Promise<void> => {
        try {
            await axios.post('/logout');
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            user.value = null;
            isAuthenticated.value = false;
            isInitialized.value = false;
            errors.value = {};
            globalError.value = null;
        }
    };

    const checkAuth = async (): Promise<void> => {
        try {
            if (!document.cookie.includes('XSRF-TOKEN')) {
                await axios.get('/sanctum/csrf-cookie');
            }
            const response = await axios.get('/api/user');
            user.value = response.data.data;
            isAuthenticated.value = true;
        } catch (error) {
            user.value = null;
            isAuthenticated.value = false;
        } finally {
            isInitialized.value = true;
        }
    };

    const updateCurrentUser = (userData: Record<string, any>) => {
        user.value = userData;
    };

    return {
        user,
        isAuthenticated,
        isInitialized,
        errors,
        globalError,
        register,
        login,
        logout,
        checkAuth,
        updateCurrentUser,
    };
});