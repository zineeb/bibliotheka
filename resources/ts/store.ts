import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import axios from "axios";

export const useAuthStore = defineStore('auth', () => {
    const token = ref<string | null>(null);
    const isLoggedIn = computed(() => token.value !== null);

    function setToken(newToken: string | null) {
        token.value = newToken;
    }

    function clearToken() {
        token.value = null;
    }

    async function checkAuth() {
        try {
            const response = await axios.get('/api/check-auth', {withCredentials: true});
            if (response.status === 200 && response.data.isAuthenticated) {
                setToken('authenticated');
            } else {
                clearToken();
            }
        } catch (error) {
            clearToken();
        }
    }

    return { token, isLoggedIn, setToken, clearToken, checkAuth};
});
