<script setup lang="ts">
import {ref, onMounted} from "vue";
import axios from "axios";
import {useRouter, useRoute} from "vue-router";

const token = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const router = useRouter();
const route = useRoute();

const submitForm = async () => {
    try {
        await axios.post('api/reset_password', {
            token: token.value,
            email: email.value,
            password: password.value,
        });
        router.push('/login');
    } catch (error) {
        if (axios.isAxiosError(error) && error.response) {
            console.error(error.response.data.errors);
        }
    }
};

onMounted(() => {
    token.value = route.query.token as string || '';
    email.value = route.query.email as string || '';
});
</script>

<template>
    <div class="reset-password">
        <div class="container">
            <h1>Réinitialisation du mot de passe</h1>
            <form @submit.prevent="submitForm">
                <input type="hidden" name="token" v-model="token">
                <input type="hidden" name="email" v-model="email">
                <div>
                    <label for="new-password">Nouveau mot de passe:</label>
                    <input type="password" id="new-password" name="new-password" v-model="password">
                </div>
                <div>
                    <label for="confirm-password">Confirmation du nouveau mot de passe:</label>
                    <input type="password" id="confirm-password" name="confirm-password" v-model="confirmPassword">
                </div>
                <button type="submit">Modifier le mot de passe</button>
            </form>
        </div>
    </div>
</template>

