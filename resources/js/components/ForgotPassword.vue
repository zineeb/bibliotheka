<template>
    <!-- Main container for the forgot-password component -->
    <div class="forgot-password">
        <div class="container-forgot">
            <!-- Title for the forgot-password form -->
            <h2>Mot de passe oublié</h2>
            <!-- Form to submit email address to request a password reset link -->
            <form @submit.prevent="sendResetLink">
                <div>
                    <!-- Label for the email input field -->
                    <label for="login-email">Email :</label>
                    <!-- Input field for the user's email address, bound to the forgotPassword.email data property -->
                    <input type="email" id="login-email" v-model="forgotPassword.email" required>
                </div>
                <!-- Submit button to send the password reset link -->
                <button type="submit">Envoyer le lien de réinitialisation</button>
            </form>
            <!-- Display the message if there is any response or error from the server -->
            <div v-if="message">{{ message }}</div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            // Message to display any response or error from the server
            message: '',
            // Object to store the email address entered by the user
            forgotPassword: {
                email: ''
            }
        };
    },
    methods: {
        // Function to send the email address to the server and request a password reset link
        async sendResetLink() {
            try {
                // Send a POST request to the api/forgot_password endpoint with the user's email address as the payload
                const response = await axios.post('api/forgot_password', {email: this.forgotPassword.email});
                console.log(response.data);
                // Display a success message if the request is successful
                this.message = 'Le lien de réinitialisation a été envoyé à votre adresse email';
            } catch (error) {
                // Handle any errors that occur during the request
                if (error.response && error.response.status === 422) {
                    console.error(error.response.data.errors);
                }
            }
        },
    },
};
</script>
