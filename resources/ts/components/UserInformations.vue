<script setup lang="ts">
import {ref, onMounted} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";
import {useRoute, useRouter} from "vue-router";
import * as events from "events";

const authStore = useAuthStore();
const route = useRoute();
const router = useRouter();
const user = ref<{ id: null | number, name: string, email: string, password: string, profileImage?: string }>({ id: null, name: '', email: '', password: '', profileImage: '' });

const fetchUser = async () => {
    const userId = route.params.id;
    const token = authStore.token;
    if (token && userId) {
        try {
            const response = await axios.get(`/api/user/${userId}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            user.value = { ...response.data, profileImage: response.data.profile_image };
        } catch (error) {
            console.error("Erreur lors de la récupération des données de l'utilisateur :", error);
        }
    }
};

const updateUser = async () => {
    const userId = route.params.id;
    const token = authStore.token;
    if (token && userId) {
        try {
            const response = await axios.put(`/api/user/${userId}`, {
                name: user.value.name,
                email: user.value.email,
                password: user.value.password,
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            alert("Vos informations ont été mises à jour avec succès.");
        } catch (error) {
            console.error("Erreur lors de la récupération des données de l'utilisateur :", error);
        }
    }
};

const handleProfileImageChange = async (event: Event) => {
    const userId = route.params.id;
    const token = authStore.token;
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file && token && userId) {
        const formData = new FormData();
        formData.append('profile_image', file);
        try {
            await axios.post(`/api/update-profile-image/${userId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${token}`
                }
            });
            fetchUser();
        } catch (error) {
            console.error('Erreur lors de la mise à jour de l\'image de profil :', error);
        }
    }
};

const deleteUser = async () => {
    const userId = route.params.id;
    const token = authStore.token;
    if (token && userId) {
        try {
            await axios.delete(`/api/user/${userId}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            localStorage.removeItem("token");
            router.push({ name: "home" });
        } catch (error) {
            console.error("Erreur lors de la suppression de l'utilisateur :", error);
        }
    }
};

onMounted(() => {
    fetchUser();
});
</script>

<template>
    <div class="user-informations">
        <div class="user-info">
            <h2>Vos informations : </h2>
            <label for="profil-image" class="profile-image">
                <img :src="`/${user.profileImage}`" alt="Image de profil" class="profile-image">
                <input type="file" id="profile-image" @change="handleProfileImageChange" style="display: none;">
            </label>
            <ul>
                <li>Nom : {{ user.name }}</li>
                <li>Email : {{ user.email }}</li>
            </ul>
        </div>
        <div class="user-update">
            <h2>Modifier vos données utilisateur : </h2>
            <form @submit.prevent="updateUser">
                <label for="name">Nom :</label>
                <input type="text" id="name" v-model="user.name">

                <label for="email">Email :</label>
                <input type="email" id="email" v-model="user.email">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" v-model="user.password">

                <button type="submit">Mettre à jour</button>
            </form>
        </div>
        <button @click="deleteUser">Supprimer mon compte</button>
    </div>
</template>
