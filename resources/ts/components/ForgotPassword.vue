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
    <div class="forgot-password">
        <div class="container-forgot">
            <h2>Mot de passe oublié</h2>
            <form @submit.prevent="sendResetLink">
                <div>
                    <label for="login-email">Email :</label>
                    <input type="email" id="login-email" v-model="forgotPassword.email" required>
                </div>
                <button type="submit">Envoyer le lien de réinitialisation</button>
            </form>
            <div v-if="message">{{ message }}</div>
        </div>
    </div>
</template>
