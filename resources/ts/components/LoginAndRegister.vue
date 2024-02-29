<script setup lang="ts">
import {ref, reactive, onMounted, computed} from "vue";
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

const showLogin = ref(true);
const recaptchaSiteKey = ref('6LcpBBokAAAAADc_7Wcm_XPCNLGGu3EUpyBJqj4J');
const recaptchaSiteSecretKey = ref('6LcpBBokAAAAAETBdJPwz8Py6M2N9TWHYOZRGOcr');
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
        if (response.data.token) {
            authStore.setToken(response.data.token);
            authStore.setUserId(response.data.userId);
            await router.push({name: 'Dashboard', query: {token: response.data.token, userId: response.data.userId}});
        }
    } catch (error) {
        if (axios.isAxiosError(error) && error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};

const isUsernameValid = computed(() => {
    return registerForm.username.length >= 3 && registerForm.username.length <= 255;
});

const isEmailValid = computed(() => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(registerForm.email);
});

const isPasswordValid = computed(() => {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(registerForm.password);
});

const isPasswordConfirmationValid = computed(() => {
    return registerForm.password_verification === registerForm.password;
});

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
        if (response.data.token) {
            authStore.setToken(response.data.token);
            authStore.setUserId(response.data.userId);
            await router.push({name: 'Dashboard', query: {token: response.data.token, userId: response.data.userId}});
        }
    } catch (error) {
        if (axios.isAxiosError(error) && error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};

const initRecaptcha = () => {
    if (window.grecaptcha && window.grecaptcha.render) {
        window.grecaptcha.render('recaptcha', {
            'sitekey': recaptchaSiteKey.value
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
    <div class="flex justify-center bg-[#475569] pt-24 pb-10 min-h-screen"> <!-- Ajout de pb-10 pour le padding en bas et enlèvement du centering vertical -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-4xl mx-4 md:mx-10 p-10"> <!-- Modification pour pleine largeur avec un max-w et ajout de padding -->

            <!-- PARTIE CONNEXION PAR DÉFAUT -->
            <div class="px-8 py-10" v-show="showLogin">
                <h3 class="text-left text-2xl font-bold text-gray-500 mb-8">Connexion</h3>
                <form @submit.prevent="login" class="space-y-6">
                    <!-- ADRESSE EMAIL -->
                    <div>
                        <input type="email" id="login-email" v-model="loginForm.email" required
                               class="w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"
                               placeholder="Email">
                    </div>
                    <!-- MOT DE PASSE -->
                    <div>
                        <input type="password" id="login-password" v-model="loginForm.password" required
                               class="w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"
                               placeholder="Mot de Passe">
                    </div>
                    <!-- BOUTON CONNEXION -->
                    <div>
                        <button type="submit"
                                class="w-full p-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Se connecter
                        </button>
                    </div>
                </form>

                <!-- PARTIE MOT DE PASSE OUBLIE -->
                <div class="text-center">
                    <router-link :to="{ name: 'ForgotPassword' }" class="text-blue-600 hover:underline">Mot de passe oublié ?</router-link>
                </div>


            </div>

            <!-- PARTIE INSCRIPTION -->
            <div class="px-8 pt-8 pb-8" v-show="!showLogin">
                <h3 class="text-left text-2xl font-bold text-gray-500 mb-4">Inscription</h3>
                <form @submit.prevent="register" enctype="multipart/form-data" class="space-y-4">
                    <!-- IMAGE DE PROFIL -->
                    <div class="flex items-center space-x-4">
                        <img :src="imageSrc" alt="Image de profil par défaut" class="w-12 h-12 rounded-full">
                        <div class="flex-1">
                            <input type="file" id="profil_picture" @change="onFileChange"
                                   class="w-full p-3 bg-transparent cursor-pointer">
                        </div>
                        <small v-if="errors.profil_picture" class="block text-red-500">{{
                                errors.profil_picture[0]
                            }}</small>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6">
                            <input type="text" id="username" v-model="registerForm.username" required
                                   class="w-full bg-transparent border-b-2 border-gray-300 text-gray-700 focus:outline-none focus:border-blue-500"
                                   placeholder="Nom d'utilisateur">
                            <small :class="{ 'text-green-500': isUsernameValid, 'text-red-500': !isUsernameValid }">
                                Le nom d'utilisateur doit comporter entre 3 et 255 caractères.
                            </small>
                            <small v-if="errors.username" class="block text-red-500">{{ errors.username[0] }}</small>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6">
                            <input type="email" id="email" v-model="registerForm.email" required
                                   class="w-full bg-transparent border-b-2 border-gray-300 text-gray-700 focus:outline-none focus:border-blue-500"
                                   placeholder="Email">
                            <small :class="{ 'text-green-500': isEmailValid, 'text-red-500': !isEmailValid }">
                                Entrez une adresse email valide.
                            </small>
                            <small v-if="errors.email" class="block text-red-500">{{ errors.email[0] }}</small>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6">
                            <input type="password" id="password" v-model="registerForm.password" required
                                   class="w-full bg-transparent border-b-2 border-gray-300 text-gray-700 focus:outline-none focus:border-blue-500"
                                   placeholder="Mot de Passe">
                            <small :class="{ 'text-green-500': isPasswordValid, 'text-red-500': !isPasswordValid }">
                                Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.
                            </small>
                            <small v-if="errors.password" class="block text-red-500">{{ errors.password[0] }}</small>
                        </div>
                        <div class="w-full md:w-1/2 px-3 mb-6">
                            <input type="password" id="password_verification"
                                   v-model="registerForm.password_verification" required
                                   class="w-full bg-transparent border-b-2 border-gray-300 text-gray-700 focus:outline-none focus:border-blue-500"
                                   placeholder="Vérification Mot de Passe">
                            <small :class="{ 'text-green-500': isPasswordConfirmationValid, 'text-red-500': !isPasswordConfirmationValid }">
                                Les mots de passe doivent correspondre.
                            </small>
                            <small v-if="errors.password_verification"
                                   class="block text-red-500">{{ errors.password_verification[0] }}</small>

                        </div>
                    </div>
                    <div class="container-recaptcha flex justify-center mt-4">
                        <div id="recaptcha" class="g-recaptcha"></div>
                    </div>
                    <div class="flex justify-center mt-4">
                        <button type="submit"
                                class="w-full md:w-auto px-10 py-3 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            S'inscrire
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bouton Connexion/Inscription avec Google -->
            <div class="px-8 py-4">
                <button @click="loginWithGoogle"
                        class="flex items-center justify-center w-full p-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-50">
                    Connexion/Inscription avec Google
                </button>
            </div>

            <!-- Toggle Buttons pour afficher soit Connexion soit Inscription -->
            <div class="flex justify-center mt-4 bg-white rounded-b-2xl">
                <button class="w-1/2 p-4 focus:outline-none"
                        :class="{'bg-blue-100': showLogin, 'rounded-tl-2xl': showLogin}" @click="showLogin = true">
                    Connexion
                </button>
                <button class="w-1/2 p-4 focus:outline-none"
                        :class="{'bg-blue-100': !showLogin, 'rounded-tl-2xl': !showLogin}" @click="showLogin = false">
                    Inscription
                </button>
            </div>

        </div>
    </div>
</template>

