<script setup lang="ts">
import {ref, reactive, onMounted} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";

const contactHook = ref<HTMLElement | null>(null)
const contact = reactive({
    name: '',
    email: '',
    message: ''
});
const router = useRouter();

const scrollToContactForm = () => {
    contactHook.value?.scrollIntoView({behavior: 'smooth'});
}

const submitForm = async () => {
    try {
        await axios.post('api/contact', contact);
        alert('Votre message a été envoyé');
        contact.name = '';
        contact.email = '';
        contact.message = '';
    } catch (error) {
        console.error(error);
        alert('Une erreur est survenue lors de l\'envoi de votre message.');
    }
};

onMounted(() => {
    if (router.currentRoute.value.query.showContactForm === 'true') {
        scrollToContactForm();
    }
});
</script>

<template>
    <div class="home">
        <div class="banner">
            <h1>Bibliothéka</h1>
            <h2>"Your bookshelf, curated"</h2>
        </div>
        <div class="presentation">
            <div class="container-pres">
                <img src="/images/bibliotheka_icon.png" alt="Logo">
                <p>
                    Bibliothéka est un projet de fin d'année de licence en développement d'applications web, réalisé par
                    une équipe de deux personnes passionnées. Notre application web s'adresse à tous les amateurs de
                    lecture qui se retrouvent parfois perdus dans leurs multiples ouvrages. On a tous connu ce moment où
                    on ne sait plus exactement où on en est dans notre lecture ou comment ranger notre bibliothèque
                    personnelle. C'est là que Bibliothéka intervient. Elle offre une solution pratique pour garder une
                    trace claire de notre progression dans nos livres. Avec Bibliothéka, on peut facilement marquer nos
                    livres comme "en cours de lecture", "terminé" ou "à lire", et ainsi avoir une vision claire de notre
                    parcours littéraire. Fini les lectures inachevées et les livres oubliés sur les étagères. Grâce à
                    Bibliothéka, on peut organiser notre bibliothèque avec facilité et suivre notre progression pas à
                    pas.
                </p>
            </div>
        </div>

        <div class="contact-form" ref="contactHook">
            <h2>Contactez-nous</h2>
            <p>"Posez vos questions ou partagez vos suggestions ici pour que notre bibliothèque en ligne continue de
                grandir et de répondre à vos besoins littéraires !"</p>
            <form @submit.prevent="submitForm">
                <label for="name">Votre nom:</label>
                <input id="name" v-model="contact.name" type="text" required/>

                <label for="email">Votre email:</label>
                <input id="email" v-model="contact.email" type="email" required/>

                <label for="message">Votre message:</label>
                <textarea id="message" v-model="contact.message" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>
</template>
