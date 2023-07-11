<template>
    <div class="user-informations">
        <!-- Display user information -->
        <div class="user-info">
            <h2>Vos informations : </h2>
            <img :src="`/${user[0].profile_image}`" alt="Image de profil">
            <ul>
                <li>Nom : {{ user[0].name }}</li>
                <li>Email : {{ user[0].email }}</li>
            </ul>
        </div>
        <!-- Form for updating user information -->
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
        // Update user information
        updateUser() {
            const userId = this.$route.params.id;
            const token = localStorage.getItem("token");

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
                    this.infosUser = response.data;
                    this.message = "Vos informations ont été mises à jour avec succès.";
                })
                .catch((error) => {
                    console.error("Erreur lors de la mise à jour des informations de l'utilisateur :", error);
                });
        },
        // Delete user account
        deleteUser() {
            const userId = this.$route.params.id;
            const token = localStorage.getItem("token");

            axios
                .delete(`/api/user/${userId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then(() => {
                    localStorage.removeItem("token");
                    this.$router.push({ name: "home" });
                })
                .catch((error) => {
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
