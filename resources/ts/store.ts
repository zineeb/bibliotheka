import {defineStore} from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: null as string | null,
    }),
    getters: {
        isLoggedIn: (state) => !!state.token,
    },
    actions: {
        setToken(newToken: string | null) {
            this.token = newToken;
        },
        clearToken() {
            this.token = null;
        },
    },
})
