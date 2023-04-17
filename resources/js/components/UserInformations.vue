<!-- UserInformations.vue -->
<template>
    <div class="user-informations">
        <div class="user-info">
            <h2>Vos informations : </h2>
            <img :src="`/${user[0].profile_image}`" alt="Image de profil">
            <ul>
                <li>Nom : {{ user[0].name }}</li>
                <li>Email : {{ user[0].email }}</li>
            </ul>
        </div>
        <div class="user-update">
            <h2>Modifier données utilisateur : </h2>
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

<script>
import axios from 'axios';

export default {
    data() {
        return {
            user: {
                id: null,
                name: '',
                email: '',
                password: '',
            }
        };
    },
    methods: {
        updateUser() {
            const userId = this.$route.params.id;
            // Assurez-vous de récupérer le token pour l'authentification
            const token = localStorage.getItem("token");

            // Remplacez `your-api-url` par l'URL de votre API pour la mise à jour de l'utilisateur
            axios
                .put(`/api/user/${userId}`, {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    // Mettre à jour les informations de l'utilisateur avec les données renvoyées par l'API
                    this.infosUser = response.data;

                    // Afficher un message de réussite
                    this.message = "Vos informations ont été mises à jour avec succès.";
                })
                .catch((error) => {
                    // Gérer les erreurs ici
                    console.error("Erreur lors de la mise à jour des informations de l'utilisateur :", error);
                });
        },
        deleteUser() {
            const userId = this.$route.params.id;
            // Assurez-vous de récupérer le token pour l'authentification
            const token = localStorage.getItem("token");

            // Remplacez `your-api-url` par l'URL de votre API pour la suppression de l'utilisateur
            axios
                .delete(`/api/user/${userId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then(() => {
                    // Supprimer le token du stockage local et déconnecter l'utilisateur
                    localStorage.removeItem("token");

                    // Rediriger vers la page principale après la suppression de l'utilisateur
                    this.$router.push({ name: "home" });
                })
                .catch((error) => {
                    // Gérer les erreurs ici
                    console.error("Erreur lors de la suppression de l'utilisateur :", error);
                });
        },
    },
    created() {
        const userId = this.$route.params.id;
        console.log(userId);
        const token = localStorage.getItem('token');

        axios
            .get(`/api/user/${userId}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
            .then(response => {
                console.log(response.data);
                this.user = response.data;
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données de l'utilisateur :", error);
            });
    }
};
</script>

<style scoped>
</style>
