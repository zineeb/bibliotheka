<template>
    Récupérer le token pour l'authentification
    <div class="container">
        <!-- Form for resetting the password -->
        <h1>Réinitialisation du mot de passe</h1>
        <form @submit.prevent="submitForm">
            <input type="hidden" name="token" v-model="token">
            <input type="hidden" name="email" v-model="email">
            <!-- New password input -->
            <div>
                <label for="password">Nouveau mot de passe:</label>
                <input type="password" id="password" name="password" v-model="password">
            </div>
            <!-- Submit button for the form -->
            <button type="submit">Modifier le mot de passe</button>
        </form>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            token: "",
            email: "",
            password: "",
        };
    },
    methods : {
        async submitForm() {
            console.log("La méthode submitForm est appelée");
            try {
                const response = await axios.post("api/reset_password", {
                    token: this.token,
                    email: this.email,
                    password: this.password,
                });
                console.log("On a réussi !")
                this.$router.push('/login'); // Redirect to login page after password reset
            } catch (error) {
                console.log("Il y a des erreurs !")
                console.error(error.response.data.errors);
            }
        },
    },
    created() {
        // Get token and email from the URL parameters
        const urlParams = new URLSearchParams(this.$route.query);
        this.token = urlParams.get("token");
        this.email = urlParams.get("email");
    },
};
</script>
