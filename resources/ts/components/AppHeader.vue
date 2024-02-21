<script setup lang="ts">
import {computed, onMounted} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";

const authStore = useAuthStore();
const isLoggedIn = computed(() => authStore.isLoggedIn);

const checkAuth = async () => {
    try {
        const response = await axios.get('api/check-auth');
        if (response.data.authenticated && response.data.token) {
            authStore.setToken(response.data.token);
        } else {
            authStore.clearToken();
        }
    } catch (error) {
        console.error('Error checking authentification:', error);
        authStore.clearToken();
    }
}

const logout = async () => {
    try {
        await axios.post('api/logout');
        authStore.clearToken();
        window.location.href = '/';

    } catch (error) {
        console.error('Error logging out:', error);
    }
}

onMounted(() => {
    checkAuth();
});
</script>

<template>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="/home">
                    <img src="/images/bibliotheka_icon.png" alt="Logo">
                </a>
            </div>
            <nav>
                <ul>
                    <li><router-link to="{ name: 'home' }">Accueil</router-link></li>
                    <li><router-link to="{ name: 'contact' }">Nous contacter</router-link></li>
                    <li><router-link to="{ name: 'dashboard' }">Mon compte</router-link></li>
                </ul>
                <li><router-link to="{ name: 'login' }" v-if="!isLoggedIn">Connexion / Inscription</router-link></li>
                <a href="/#" @click.prevent="logout" v-if="isLoggedIn">Déconnexion</a>
            </nav>
        </div>
    </header>
</template>

