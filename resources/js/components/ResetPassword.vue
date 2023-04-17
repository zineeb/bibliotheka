<template>
    <div class="container">
        <h1>Réinitialisation du mot de passe</h1>
        <form @submit.prevent="submitForm">
            <input type="hidden" name="token" v-model="token">
            <input type="hidden" name="email" v-model="email">
            <div>
                <label for="password">Nouveau mot de passe:</label>
                <input type="password" id="password" name="password" v-model="password">
            </div>
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
                this.$router.push('/login'); // Rediriger vers la page de connexion après la réinitialisation du mot de passe
            } catch (error) {
                console.log("Il y a des erreurs !")
                console.error(error.response.data.errors);
            }
        },
    },
    created() {
        const urlParams = new URLSearchParams(this.$route.query);
        this.token = urlParams.get("token");
        this.email = urlParams.get("email");
    },
};
</script>
