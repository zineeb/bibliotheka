<template>
    <div class="forgot-password">
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
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            message: '',
            forgotPassword: {
                email: ''
            }
        };
    },
    methods: {
        async sendResetLink() {
            try {
                const response = await axios.post('api/forgot_password', {email: this.forgotPassword.email});
                console.log(response.data);
                this.message = 'Le lien de réinitialisation a été envoyé à votre adresse email';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    console.error(error.response.data.errors);
                }
            }
        },
    },
};
</script>
