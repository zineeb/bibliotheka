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

        <div class="contact-form" ref="contact-hook">
            <h2>Contactez-nous</h2>
            <form @submit.prevent="submitForm">
                <label for="name">Votre nom:</label>
                <input id="name" v-model="contact.name" type="text" required />

                <label for="email">Votre email:</label>
                <input id="email" v-model="contact.email" type="email" required />

                <label for="message">Votre message:</label>
                <textarea id="message" v-model="contact.message" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>

    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'Home',
    data() {
        return {
            contact: {
                name: '',
                email: '',
                message: ''
            }
        }
    },
    methods: {
        async submitForm() {
            try {
                await axios.post('api/contact', this.contact);
                alert('Votre message a été envoyé!');
                this.contact.name = '';
                this.contact.email = '';
                this.contact.message = '';
            } catch(error) {
                console.error(error);
                alert('Une erreur est survenue lors de l\'envoi de votre message.');
            }
        },
        scrollIntoView() {
            const anchorElement = document.querySelector('#ancreContact');
            anchorElement.scrollIntoView({ behavior: 'smooth' });
        }
    },
    mounted() {
        this.$root.$on('scroll-to-anchor', this.scrollIntoView);
    },
};
</script>
