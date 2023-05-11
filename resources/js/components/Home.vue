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
                    Porem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis
                    tellus. Sed dignissim, metus nec fringilla accumsan, risus sem sollicitudin lacus, ut interdum tellus
                    elit sed risus. Maecenas eget condimentum velit, sit amet feugiat lectus. Class aptent taciti sociosqu
                    ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent auctor purus luctus enim
                    egestas, ac scelerisque ante pulvinar. Donec ut rhoncus ex. Suspendisse ac rhoncus nisl, eu tempor urna.
                    Curabitur vel bibendum lorem. Morbi convallis convallis diam sit amet lacinia. Aliquam in elementum
                    tellus.
                </p>
            </div>
        </div>

        <div class="contact-form">
            <h2>Contactez-nous</h2>
            <form @submit.prevent="submitForm">
                <label for="name">Votre nom:</label>
                <input id="name" v-model="contact.name" type="text" required>

                <label for="email">Votre email:</label>
                <input id="email" v-model="contact.email" type="email" required>

                <label for="message">Votre message:</label>
                <textarea id="message" v-model="contact.message" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>

    </div>
</template>

<script>
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
        }
    }
};
</script>
