<script setup lang="ts">
import {ref, reactive, onMounted} from "vue";
import {useAuthStore} from "@/store";
import axios from "axios";
import {useRouter} from "vue-router";

declare global {
    interface Window {
        grecaptcha: any;
    }
}

interface FormErrors {
    email?: string[];
    password?: string[];
    profil_picture?: string[];
    username?: string[];
    password_verification?: string[];
}

const recaptchaSiteKey = ref('6LcpBBokAAAAADc_7Wcm_XPCNLGGu3EUpyBJqj4J');
const authStore = useAuthStore();
const router = useRouter();
const errors = ref<FormErrors>({});
const loginForm = reactive({
    email: '',
    password: ''
});
const registerForm = reactive({
    username: '',
    email: '',
    password: '',
    password_verification: '',
    profil_picture: '',
    recaptcha: ''
});
const imageSrc = ref('/images/utilisateur.png');
const selectedFile = ref<File | null>(null);

const login = async () => {
    try {
        const response = await axios.post('api/login', loginForm);
        if (response.data.status === 'success') {
            authStore.setToken(response.data.token);
            router.push('/dashboard').catch(err => {});
        }
    } catch (error) {
        if (axios.isAxiosError(error) && error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};

const register = async () => {
    try {
        const formData = new FormData();

        Object.entries(registerForm).forEach(([key, value]) => {
            formData.append(key, value);
        });

        if (selectedFile.value) {
            formData.append('profil_picture', selectedFile.value);
        }

        const response = await axios.post('api/register', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        if (response.data.status === 'success') {
            authStore.setToken(response.data.token);
            router.push('/dashboard').catch(err => {});
        }
    } catch (error) {
        if (axios.isAxiosError(error) && error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};

const initRecaptcha = () => {
    if (window.grecaptcha) {
        window.grecaptcha.render('recaptcha', {
            sitekey: recaptchaSiteKey
        });
    } else {
        setTimeout(initRecaptcha, 100);
    }
};

const loginWithGoogle = () => window.location.href = 'http://localhost:8000/auth/google';

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files?.[0]) {
        selectedFile.value = target.files[0];
        imageSrc.value = URL.createObjectURL(selectedFile.value);
    }
}

onMounted(() => {
    initRecaptcha();
})
</script>

<template>
    <div class="login-and-register">
        <div class="container-login">
            <div class="login-section">
                <h2>Connexion</h2>
                <form @submit.prevent="login">
                    <div>
                        <label for="login-email">Email :</label>
                        <input type="email" id="login-email" placeholder="Email" v-model="loginForm.email" required>
                        <small v-if="errors.email">{{ errors.email[0] }}</small>
                    </div>
                    <div>
                        <label for="login-password">Mot de passe :</label>
                        <input type="password" id="login-password" placeholder="Password" v-model="loginForm.password" required>
                        <small v-if="errors.password">{{ errors.password[0] }}</small>
                    </div>
                    <button type="submit">Se connecter</button>
                    <div>
                        <router-link to="/forgot-password">Mot de passe oublié ?</router-link>
                    </div>
                    <div>
                        <button type="button" @click="loginWithGoogle">Se connecter avec Google</button>
                    </div>
                </form>
            </div>
            <div id="line-vertical"></div>
            <div class="register-section">
                <h2>Inscription</h2>
                <form @submit.prevent="register" enctype="multipart/form-data">
                    <div>
                        <img :src="imageSrc" alt="Image de profil par défaut" width="50">
                        <label for="profil_picture">Image de profil :</label>
                        <input type="file" id="profil_picture" @change="onFileChange">
                        <small v-if="errors.profil_picture">{{ errors.profil_picture[0] }}</small>
                    </div>
                    <div class="container-recaptcha">
                        <div id="recaptcha" class="g-recaptcha"></div>
                    </div>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</template>
