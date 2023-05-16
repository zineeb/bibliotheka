<template>
    <!-- Main container for the login and registration component -->
    <div class="login-and-register">
        <div class="container-login">
            <!-- Login section -->
            <div class="login-section">
                <h2>Connexion</h2>
                <!-- Form to submit login credentials -->
                <form @submit.prevent="login">
                    <div>
                        <label for="login-email">Email :</label>
                        <input type="email" id="login-email" placeholder="Email" v-model="loginForm.email" required>
                        <!-- Display email errors if any -->
                        <small v-if="errors.email">{{ errors.email[0] }}</small>
                    </div>
                    <div>
                        <label for="login-password">Mot de passe :</label>
                        <input type="password" id="login-password" placeholder="Password" v-model="loginForm.password" required>
                        <!-- Display password errors if any -->
                        <small v-if="errors.password">{{ errors.password[0] }}</small>
                    </div>
                    <!-- Button to submit login form -->
                    <button type="submit">Se connecter</button>
                    <div>
                        <!-- Link to navigate to the forgot-password component -->
                        <router-link to="/forgot-password">Mot de passe oublié ?</router-link>
                    </div>
                    <div>
                        <!-- Button to log in with Google -->
                        <button @click="loginWithGoogle">Se connecter avec Google</button>
                    </div>
                </form>
            </div>
            <div id="line-vertical"></div>
            <!-- Registration section -->
            <div class="register-section">
                <h2>Inscription</h2>
                <!-- Form to submit registration details -->
                <form @submit.prevent="register" enctype="multipart/form-data">
                    <div>
                        <div>
                            <!-- Display user's selected profile image or default image -->
                            <img :src="imageSrc" alt="Image de profil par défaut" width="50">
                        </div>
                        <div>
                            <label for="profil_picture">Image de profil :</label>
                            <input type="file" id="profil_picture" @change="onFileChange">
                            <!-- Display profile picture errors if any -->
                            <small v-if="errors.profil_picture">{{ errors.profil_picture[0] }}</small>
                        </div>
                    </div>
                    <div>
                        <label for="username">Nom d'utilisateur :</label>
                        <input type="text" id="username" v-model="registerForm.username" required minlength="4" placeholder="Nom d'utilisateur">
                        <!-- Display username errors if any -->
                        <small v-if="errors.username">{{ errors.username[0] }}</small>
                        <small>  Le nom d’utilisateur doit faire plus de 3 caractères.</small>
                    </div>
                    <div>
                        <label for="email">Email :</label>
                        <input type="email" id="email" v-model="registerForm.email" required placeholder="Email">
                        <!-- Display email errors if any -->
                        <small v-if="errors.email">{{ errors.email[0] }}</small>
                        <small>  L’adresse email doit être valide.</small>
                    </div>
                    <div>
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" v-model="registerForm.password" required :pattern="passwordPattern" placeholder="Mot de passe">
                        <!-- Display password errors if any -->
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
                    <!-- Google reCAPTCHA container -->
                    <div id="recaptcha" class="g-recaptcha" :data-sitekey="recaptchaSiteKey"></div>
                    <!-- Button to submit registration form -->
                    <button type="submit">S'inscrire</button>
                </form>
                <div>
                    <!-- Button to sign up with Google -->
                    <button @click="loginWithGoogle">S'inscrire avec Google</button>
                </div>
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
        // Method to log in the user
        // Method to log in the user
        async login() {
            try {
                const response = await axios.post('api/login', this.loginForm);
                console.log(response.data);
                if (response.data.status === 'success') {
                    localStorage.setItem('token', response.data.token);
                    this.$router.push('/dashboard');
                    this.$emit('user-logged-in'); // Emit a custom event when the user is logged in successfully
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    console.error(error.response.data.errors);
                }
            }
        },
        // Method to register a new user
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
        // Method to log in or register with Google
        async loginWithGoogle() {
            // Remplacez cette URL par l'URL de redirection vers Google de votre application backend
            const googleAuthUrl = 'http://localhost:8000/auth/google';
            window.location.href = googleAuthUrl;
        }
        ,
        // Method to handle file changes for the profile picture
        onFileChange(event) {
            this.selectedFile = event.target.files[0]
            this.imageSrc = URL.createObjectURL(this.selectedFile)
        },
        // Method to initialize Google reCAPTCHA
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
    // Lifecycle hook to initialize reCAPTCHA when the component is mounted
    mounted() {
        this.initRecaptcha();
    },
};
</script>
