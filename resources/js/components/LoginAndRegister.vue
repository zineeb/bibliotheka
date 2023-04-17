<template>
    <div class="login-and-register">
        <div class="login-section">
            <h2>Connexion</h2>
            <form @submit.prevent="login">
                <div>
                    <label for="login-email">Email :</label>
                    <input type="email" id="login-email" v-model="loginForm.email" required>
                    <small v-if="errors.email">{{ errors.email[0] }}</small>
                </div>
                <div>
                    <label for="login-password">Mot de passe :</label>
                    <input type="password" id="login-password" v-model="loginForm.password" required>
                    <small v-if="errors.password">{{ errors.password[0] }}</small>
                </div>
                <button type="submit">Se connecter</button>
            </form>
            <div>
                <router-link to="/forgot-password">Mot de passe oublié ?</router-link>
            </div>
            <div>
                <button @click="loginWithGoogle">Se connecter avec Google</button>
            </div>
        </div>
        <div class="register-section">
            <h2>Inscription</h2>
            <form @submit.prevent="register" enctype="multipart/form-data">
                <div>
                    <div>
                        <img :src="imageSrc" alt="Image de profil par défaut" width="50">
                    </div>
                    <div>
                        <label for="profil_picture">Image de profil :</label>
                        <input type="file" id="profil_picture" @change="onFileChange">
                        <small v-if="errors.profil_picture">{{ errors.profil_picture[0] }}</small>
                    </div>
                </div>
                <div>
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" v-model="registerForm.username" required minlength="4" placeholder="Nom d'utilisateur">
                    <small v-if="errors.username">{{ errors.username[0] }}</small>
                    <small>  Le nom d’utilisateur doit faire plus de 3 caractères.</small>
                </div>
                <div>
                    <label for="email">Email :</label>
                    <input type="email" id="email" v-model="registerForm.email" required placeholder="Email">
                    <small v-if="errors.email">{{ errors.email[0] }}</small>
                    <small>  L’adresse email doit être valide.</small>
                </div>
                <div>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" v-model="registerForm.password" required :pattern="passwordPattern" placeholder="Mot de passe">
                    <small v-if="errors.password">{{ errors.password[0] }}</small>
                    <small>  Le mot de passe doit comporter :
                    <ul>
                        <li>Au minimum 8 caractères.</li>
                        <li>Une lettre en majuscules.</li>
                        <li>Un chiffre.</li>
                        <li>Un caractère spécial.</li>
                    </ul>
                    </small>
                </div>
                <div id="recaptcha" class="g-recaptcha" :data-sitekey="recaptchaSiteKey"></div>
                <button type="submit">S'inscrire</button>
            </form>
            <div>
                <button @click="loginWithGoogle">S'inscrire avec Google</button>
            </div>
        </div>
    </div>
</template>

<script>
import { CAPTCHA_SITE_KEY } from '@/../../env.js';
import axios from 'axios';

export default {
    data() {
        return {
            message: '',
            errors: {},
            loginForm: {
                email: '',
                password: ''
            },
            registerForm: {
                username: '',
                email: '',
                password: '',
                profil_picture: '',
                recaptcha: ''
            },
            imageSrc: '/images/utilisateur.png',
            recaptchaSiteKey: CAPTCHA_SITE_KEY
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('api/login', this.loginForm);
                console.log(response.data);
                if (response.data.status === 'success') {
                    localStorage.setItem('token', response.data.token);
                    this.$router.push('/dashboard');
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    console.error(error.response.data.errors);
                }
            }
        },
        async register() {
            try {
                const response = await axios.post('api/register', this.registerForm);
                console.log(response.data);
                if (response.data.status === 'success') {
                    localStorage.setItem('token', response.data.token);
                    this.$router.push('/dashboard');
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    // Mettez à jour les erreurs dans l'objet `errors`
                    this.errors = error.response.data.errors;
                }
            }
        },
        async loginWithGoogle() {
            try {
                const response = await axios.get('/auth/google');
                if (response.data.status === 'success') {
                    localStorage.setItem('token', response.data.token);
                    this.$router.push('/dashboard');
                }
            } catch (error) {
                console.error('Erreur lors de la connexion avec Google :', error);
            }
        },
        onFileChange(event) {
            this.selectedFile = event.target.files[0]
            this.imageSrc = URL.createObjectURL(this.selectedFile)
        },
        initRecaptcha() {
            if (window.grecaptcha) {
                window.grecaptcha.render('recaptcha', {
                    'sitekey': this.recaptchaSiteKey
                });
            } else {
                setTimeout(() => {
                    this.initRecaptcha();
                }, 100);
            }
        },
    },
    mounted() {
        this.initRecaptcha();
    },
};
</script>
