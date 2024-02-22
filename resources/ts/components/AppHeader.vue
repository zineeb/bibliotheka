<script setup lang="ts">
import {computed, onMounted} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";

const authStore = useAuthStore();
const isLoggedIn = computed(() => authStore.isLoggedIn);

const checkAuth = async () => {
    try {
        const response = await axios.get('/api/check-auth');
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
        await axios.post('/api/logout');
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
    <!-- COMPOSITION DU HEADER :  header transparent -->
    <header class="fixed top-0 w-full z-10 bg-transparent text-black flex justify-between items-center px-5 py-3 header-font">
        <!-- PREMIERE PARTIE : nom du site -->
        <div class="text-lg text-custom-yellow">
                Bibliotheka
        </div>

        <!-- DEUXIEME PARTIE : navigation -->
        <nav>
            <ul class="flex space-x-8">
                <li class="mx-4 nav-link text-lg" v-if="$route.name !== 'Home' && $route.name !== 'HomeAlias'"><router-link :to="{ name: 'Home' }">Home</router-link></li>
<!--                <li class="nav-link"><router-link :to="{ name: 'Contact' }">Contact</router-link></li>-->
                <li class="mx-4 nav-link text-lg" v-if="isLoggedIn"><router-link :to="{ name: 'Dashboard' }">Dashboard</router-link></li>
                <li class="mx-4 nav-link text-lg" v-if="isLoggedIn"><router-link :to="{ name: 'UserProfile' }">Profile</router-link></li>
                <li class="mx-4 nav-link text-lg" v-if="!isLoggedIn"><router-link :to="{ name: 'LoginAndRegister' }">Login / Sign-in</router-link></li>
                <li class="mx-4 nav-link text-lg" v-if="isLoggedIn" @click.prevent="logout">Logout</li>
            </ul>
        </nav>
    </header>
</template>


