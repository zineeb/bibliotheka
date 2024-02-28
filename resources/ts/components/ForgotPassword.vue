<script setup lang="ts">
import { ref } from 'vue';
import axios, { AxiosError } from 'axios';

const message = ref<string>('');
const forgotPassword = ref({ email: '' });

const sendResetLink = async () => {
    try {
        const response = await axios.post('/api/forgot_password', { email: forgotPassword.value.email });
        message.value = 'Le lien de réinitialisation a été envoyé à votre adresse email';
    } catch (error) {
        const axiosError = error as AxiosError;
        if (axiosError.response && axiosError.response.status === 422) {
            console.error(axiosError.response.data.errors);
            message.value = 'Erreur lors de l’envoi du lien de réinitialisation.';
        } else {
            console.error(error);
            message.value = 'Une erreur s’est produite. Veuillez réessayer plus tard.';
        }
    }
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-[#475569]">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-md mx-4 md:mx-0">

            <div class="px-8 py-10">
                <h3 class="text-left text-2xl font-bold text-gray-500 mb-8">Mot de passe oublié : </h3>
                <form @submit.prevent="sendResetLink" class="space-y-6">
                    <!-- ADRESSE EMAIL -->
                    <div>
                        <label for="login-email" class="block text-sm font-medium text-gray-700">Adresse Email : </label>
                        <input type="email" id="login-email" v-model="forgotPassword.email" required
                               class="w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"
                               placeholder="Email">
                    </div>
                    <!-- BOUTON CONNEXION -->
                    <div>
                        <button type="submit"
                                class="w-full p-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Envoyer le lien de réinitialisation
                        </button>
                    </div>
                </form>
                <div v-if="message">{{ message }}</div>
            </div>
        </div>
    </div>
</template>

