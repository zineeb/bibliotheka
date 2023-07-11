<template>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="/home">
                    <img src="/images/bibliotheka_icon.png" alt="Logo">
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/home">Accueil</a></li>
                    <!-- <li><a href="#">Statistiques</a></li> (V2) -->
                    <li><a href="/dashboard">Mon Compte</a></li>
                </ul>
                <a href="/loginAndRegister" v-if="!isLoggedIn">Connexion / Inscription </a>
                <a href="/#" @click.prevent="logout" v-if="isLoggedIn">Déconnexion </a>
            </nav>
        </div>
    </header>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AppHeader',
    data() {
        return {
            isLoggedIn: false
        };
    },
    created() {
        this.checkAuth();
    },
    methods: {
        async checkAuth() {
            try {
                const response = await axios.get('api/check-auth');
                console.log('API response:', response);
                console.log('Authenticated:', response.data.authenticated); // Ajouter un log ici
                this.isLoggedIn = response.data.authenticated;
                console.log('isLoggedIn:', this.isLoggedIn); // Ajouter un log ici
            } catch (error) {
                console.error('Error checking authentification:', error);
            }
        },
        async logout() {
            try {
                await axios.post('api/logout');
                this.isLoggedIn = false;
                localStorage.removeItem('token');
                window.location.href = '/';
            } catch (error) {
                console.error('Error logging out:', error);
            }
        }
    }
};
</script>
