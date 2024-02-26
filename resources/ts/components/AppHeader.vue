<script setup lang="ts">
import {useRoute} from "vue-router";
import {useAuthStore} from "@/store";
import {computed, onMounted} from "vue";
import axios from "axios";

const route = useRoute();
const authStore = useAuthStore();

const isLoggedIn = computed(() => authStore.isLoggedIn);
const isHomeRoute = computed(() => route.name === 'Home' || route.name === 'HomeAlias');

const getRouteName = (targetRouteName: string) => {
    return isLoggedIn.value ? targetRouteName : 'LoginAndRegister';
}

const logout = async () => {
    try {
        await axios.post('api/logout', {}, {
            headers: {
                'Authorization': `Bearer ${authStore.token}`,
            }
        });
        authStore.clearToken();
        window.location.href = '/';
    } catch (error) {
        console.error('Error logging out:', error);
    }
}


</script>

<template>
    <header class="fixed top-0 left-0 w-full flex justify-between items-center px-5 py-3 bg-transparent text-[#0f172a] z-10">
        <nav class="w-full">
            <ul class="flex justify-around space-x-4 uppercase">
                <li v-if="!isHomeRoute" class="nav-link">
                    <router-link :to="{ name: 'Home' }">Home</router-link>
                </li>
                <li class="nav-link" v-if="authStore.userId">
                    <router-link :to="{ name: getRouteName('Dashboard') }">Dashboard</router-link>
                </li>
                <li class="nav-link" v-if="authStore.userId">
                    <router-link :to="{ name: 'UserProfile', params: { id: authStore.userId }}">Profile</router-link>
                </li>
                <li v-if="!isLoggedIn" class="nav-link">
                    <router-link :to="{ name: 'LoginAndRegister' }">Login / Sign-in</router-link>
                </li>
                <li v-if="isLoggedIn" class="nav-link" @click.prevent="logout">Logout</li>
            </ul>
        </nav>
    </header>
</template>


