import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    const token = ref<string | null>(localStorage.getItem('authToken'));
    const userId = ref<number | null>(null);
    const isLoggedIn = computed(() => token.value !== null);

    function setToken(newToken: string | null) {
        token.value = newToken;
        if (newToken) {
            localStorage.setItem('authToken', newToken);
        } else {
            localStorage.removeItem('authToken');
        }
    }

    function setUserId(newUserId: number | null) {
        userId.value = newUserId;
    }

    function clearToken() {
        token.value = null;
        userId.value = null;
        localStorage.removeItem('authToken');
    }

    function initializeFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const tokenFromUrl = urlParams.get('token');
        const userIdFromUrl = urlParams.get('userId');
        if (tokenFromUrl) {
            setToken(tokenFromUrl);
            setUserId(userIdFromUrl ? parseInt(userIdFromUrl) : null);
            window.history.pushState({}, '', '/dashboard');
        }
    }


    return { token, userId, isLoggedIn, setToken, clearToken, initializeFromUrl };
});
