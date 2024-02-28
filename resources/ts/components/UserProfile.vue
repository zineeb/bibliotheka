<script setup lang="ts">
import {ref, onMounted, UnwrapRef} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";
import {useRoute, useRouter} from "vue-router";
import Modal from "@components/Modal.vue";

const authStore = useAuthStore();
const route = useRoute();
const router = useRouter();
const showModal = ref(false);

const user = ref<{ id: null | number, name: string, email: string, password: string, profileImage?: string }>({
    id: null,
    name: '',
    email: '',
    password: '',
    profileImage: ''
});

const fetchUser = async () => {
    const userId = route.params.id;
    try {
        const response = await axios.get(`/api/user/${userId}`, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });
        user.value = {...response.data[0], profileImage: response.data[0].profile_image};
    } catch (error) {
        console.error("Erreur lors de la récupération des données de l'utilisateur :", error);
    }

};


const updateUser = async () => {
    const userId = route.params.id;
    try {
        const userData = {
            name: user.value.name,
            email: user.value.email,
            ...(user.value.password && {password: user.value.password}),
        };

        const response = await axios.put(`/api/user/${userId}`, userData, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });

        user.value = {...user.value, ...response.data};
    } catch (error) {
        console.error("Erreur lors de la mise à jour des données de l'utilisateur :", error);
    }
};

const getImageSrc = (imagePath: UnwrapRef<string | undefined> | undefined) => {
    if (/^(https?:\/\/)/.test(imagePath as string)) {
        return imagePath;
    } else {
        return `${window.location.origin}/${imagePath}`;
    }
};



const handleProfileImageChange = async (event: Event) => {
    const userId = route.params.id;
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_image', file);
        try {
            await axios.post(`/api/update-profile-image/${userId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${authStore.token}`
                }
            });
            await fetchUser();
        } catch (error) {
            console.error('Erreur lors de la mise à jour de l\'image de profil :', error);
        }
    }
};

const deleteUser = async () => {
    const userId = route.params.id;

    try {
        await axios.delete(`/api/user/${userId}`, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });
        authStore.clearToken();
        await router.push('/');
    } catch (error) {
        console.error("Erreur lors de la suppression de l'utilisateur :", error);
    }

};

onMounted(() => {
    fetchUser();
});
</script>

<template>
    <div class="pt-24 min-h-screen bg-gray-100 -4">
        <div class="mx-auto max-w-7xl">
            <!-- Titre -->
            <h1 class="text-2xl font-bold">Profil utilisateur</h1>
            <hr class="my-4 border-t-2 border-gray-200">

            <!-- Informations basiques -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Informations basiques</h3>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse Email</label>
                    <input type="email" id="email" v-model="user.email"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white"
                           :placeholder="user.email">
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                    <input type="password" id="password" v-model="user.password"
                           class="
mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white"
                           placeholder="Entrez un nouveau mot de passe">
                </div>
            </div>
            <hr class="border-gray-300">

            <!-- Information du profil -->
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Information du profil</h3>
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-24 w-24 rounded-full overflow-hidden bg-gray-200 mr-4">
                        <img :src="getImageSrc(user.profileImage)" alt="Image de profil" class="object-cover">
                    </div>
                    <div class="flex-1">
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Photo de
                            profil</label>
                        <div class="flex items-center">
                            <label class="cursor-pointer text-blue-500">
                                <input type="file" id="profile_picture" @change="handleProfileImageChange"
                                       class="hidden">
                                <span
                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-5 font-medium text-gray-700 bg-gray-100 hover:bg-gray-50">Changer la photo</span>
                            </label>
                        </div>
                        <div class="mt-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Nom
                                d'utilisateur</label>
                            <input type="text" id="username" v-model="user.name"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white"
                                   :placeholder="user.name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-2">
                <button @click="updateUser"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Modifier
                </button>
                <button @click="deleteUser"
                        class="px-4 py-2 border border-red-600 rounded-md shadow-sm text-sm font-medium text-red-600 bg-white hover:bg-red-50">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

</template>

