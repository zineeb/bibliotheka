<template>
    <div class="user-informations">
        <!-- Display user information -->
        <div class="user-info">
            <h2>Vos informations : </h2>
            <label for="profile-image" class="profile-image-label">
                <img :src="`/${user[0].profile_image}`" alt="Image de profil" class="profile-image">
                <input type="file" id="profile-image" @change="handleProfileImageChange" style="display: none;">
            </label>
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
    name: 'UserInformations',
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
        fetchUser() {
            const userId = this.$route.params.id;
            const token = localStorage.getItem("token");
            if (token) {
                axios
                    .get(`/api/user/${userId}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then(response => {
                        this.user = response.data;
                    })
                    .catch(error => {
                        console.error("Erreur lors de la récupération des données de l'utilisateur :", error);
                    });
            }
        },
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
        handleProfileImageChange(event) {
            const userId = this.$route.params.id;
            const token = localStorage.getItem("token");
            const file = event.target.files[0];

            const formData = new FormData();
            formData.append('profile_image', file);

            axios.post(`/api/update-profile-image/${userId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => {
                    this.fetchUser();
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour de l\'image de profil :', error);
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
        this.fetchUser();
    },
};
</script>

<style scoped>
</style>
