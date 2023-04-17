<template>
    <div class="dashboard">
        <div class="user-info">
            <h2>Informations de l'utilisateur : </h2>
            <img :src="infosUser[0].profile_image" alt="Image de profil">
            <ul>
                <li>Nom : {{ infosUser[0].name }}</li>
                <li>Email : {{ infosUser[0].email }}</li>
            </ul>
            <router-link :to="'/user-informations/' + infosUser[0].id">Voir et modifier mes informations</router-link>
        </div>

        <div class="books">
            <h2>Livres : </h2>
            <table>
                <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date de publication</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="book in booksUser" :key="book.id">
                    <td>{{ book.title }}</td>
                    <td>{{ book.author }}</td>
                    <td>{{ book.publication_date }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <button @click="showModalCategory = true">Ajouter Categorie</button>
        <button @click="showModalBook = true">Ajouter Livre</button>

        <modal v-if="showModalCategory" @close="showModalCategory = false">
            <template v-slot:header>
                <h3>Ajout Catégorie :</h3>
            </template>
            <template v-slot:body>
                <input type="text" v-model="modalCategoryInput" placeholder="Catégorie ..."/>
            </template>
            <template v-slot:footer>
                <button @click="submitModalCategory">Envoyer</button>
            </template>
        </modal>

        <modal v-if="showModalBook" @close="showModalBook = false">
            <template v-slot:header>
                <h3>Ajout Livre</h3>
            </template>
            <template v-slot:body>
                <input type="text" v-model="modalTitleInput" placeholder="Titre livre ..."/><br>
                <input type="text" v-model="modalAuthorInput" placeholder="Auteur"/><br>
                <input type="text" v-model="modalISBNInput" placeholder="ISBN"/><br>
            </template>
            <template v-slot:footer>
                <button @click="submitModalBook">Envoyer</button>
            </template>
        </modal>

        <modal v-if="showModalBookInfo" @close="showModalBookInfo = false">
            <template v-slot:header>
                <h3>Informations du livre</h3>
            </template>
            <template v-slot:body>
                <div v-if="bookInfo">
                    <img :src="bookInfo.coverimage" alt="Couverture du livre"/>
                    <h4>{{ bookInfo.title }}</h4>
                    <p>Auteur : {{ bookInfo.author }}</p>
                    <p>Date de publication : {{ bookInfo.publication_date }}</p>
                    <p>Description : {{ bookInfo.description }}</p>
                    <p>Maison d'édition : {{ bookInfo.publisher }}</p>
                    <p>Nombre de page : {{ bookInfo.page_count }}</p>
                    <p>Genre : {{ bookInfo.genre }}</p>

                    <input type="hidden" v-model="bookInfo.isbn"/>
                    <input type="hidden" v-model="bookInfo.google_books_id"/>

                    <div v-if="reviews.length > 0">
                        <h4>Avis récents :</h4>
                        <ul>
                            <li v-for="(review, index) in reviews" :key="index">
                                Note : {{ ratings[index] }} - {{ review }}
                            </li>
                        </ul>
                    </div>

                    <label for="reading-status">État de lecture :</label>
                    <select v-model="readingStatus" @change="handleReadingStatusChange" id="reading-status">
                        <option value="to_read">À lire</option>
                        <option value="reading">En cours de lecture</option>
                        <option value="read">Lu</option>
                    </select>

                    <label for="category">Catégorie :</label>
                    <input type="text" v-model="category" id="category" placeholder="Catégorie"/>

                    <div v-if="readingStatus === 'read'">
                        <label for="rating">Note (1-5) :</label>
                        <select v-model="rating" id="rating">
                            <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                        </select>

                        <label for="review">Avis :</label>
                        <input type="text" v-model="review" id="review" placeholder="Votre avis sur le livre"/>
                    </div>

                    <div v-if="readingStatus === 'reading'">
                        <label for="current_page">Page actuelle :</label>
                        <input type="number" v-model="current_page" id="current_page" min="1" :max="bookInfo.page_count" placeholder="Page actuelle"/>
                    </div>

                    <button @click="addBooks">Ajouter</button>
                </div>
            </template>
            <template v-slot:footer>
                <button @click="showModalBookInfo = false">Fermer</button>
            </template>
        </modal>
    </div>
</template>

<script>
import axios from "axios";
import Modal from "./Modal.vue";

export default {
    name: "Dashboard",
    components: {
        Modal,
    },
    data() {
        return {
            infosUser: {},
            booksUser: [],
            showModalCategory: false,
            showModalBook: false,
            showModalBookInfo: false,
            modalCategoryInput: "",
            modalTitleInput: "",
            modalAuthorInput: "",
            modalISBNInput: "",
            bookInfo: null,
            readingStatus: "to_read",
            category: "",
            rating: 1,
            review: "",
            reviews: [],
            ratings: [],
            id: null,
            current_page: 1,
        };
    },
    methods: {
        submitModalCategory() {
            console.log("Données soumises pour Modal Category:", this.modalCategoryInput);

            // Préparez les données à envoyer
            const data = {
                category: this.modalCategoryInput,
            };

            // Récupérez le token JWT de l'utilisateur
            const token = localStorage.getItem("token");

            // Effectuez la requête POST à l'API
            axios
                .post("api/addcategory", data, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    // Gérez la réponse de l'API
                    if (response.data.okay) {
                        console.log(response.data.okay);
                    } else if (response.data.notification) {
                        console.log(response.data.notification);
                    }
                    this.showModalCategory = false;
                    this.modalCategoryInput = "";
                })
                .catch((error) => {
                    console.error("Erreur lors de l'ajout de la catégorie :", error);
                    this.showModalCategory = false;
                    this.modalCategoryInput = "";
                });
        },
        submitModalBook() {
            console.log("Données soumises pour Modal 2:", this.modalTitleInput, this.modalAuthorInput, this.modalISBNInput);

            const data = {
                title: this.modalTitleInput,
                author: this.modalAuthorInput,
                isbn: this.modalISBNInput,
                user_id: this.$route.params.id,
            };

            const token = localStorage.getItem("token");

            axios
                .post("api/researchbook", data, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    if (response.data.status === "success") {
                        this.bookInfo = response.data.book;
                        this.reviews = response.data.review;
                        this.ratings = response.data.rating;
                        this.showModalBookInfo = true;
                    } else if (response.data.errors) {
                        console.log(response.data.errors);
                    }
                    this.showModalBook = false;
                    this.modalTitleInput = "";
                    this.modalAuthorInput = "";
                    this.modalISBNInput = "";
                })
                .catch((error) => {
                    console.error("Erreur lors de la recherche du livre :", error);
                    this.showModalBook = false;
                    this.modalTitleInput = "";
                    this.modalAuthorInput = "";
                    this.modalISBNInput = "";
                });
        },
        handleReadingStatusChange() {
            if (this.readingStatus !== "read") {
                this.rating = 1;
                this.review = "";
            }
        },
        addBooks() {
            const token = localStorage.getItem("token");
            if (!this.bookInfo) return;

            axios.post("/api/addBook", {
                google_books_id: this.bookInfo.google_books_id,
                title: this.bookInfo.title,
                author: this.bookInfo.author,
                publication_date: this.bookInfo.publication_date,
                cover_image: this.bookInfo.cover_image,
                isbn: this.bookInfo.isbn,
                reading_status: this.readingStatus,
                category: this.category,
                rating: this.rating,
                review: this.review,
                current_page: this.current_page,
            }, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
                .then(response => {
                    if (response.data.status === 'success') {
                        this.showModalBookInfo = false;
                    } else {
                        // Gérer les erreurs, par exemple afficher un message d'erreur
                    }
                })
                .catch(error => {
                    console.error("Erreur lors de la mise à jour des informations de l'utilisateur :", error);
                });
        },
    },

    created() {
        const token = localStorage.getItem("token");
        axios
            .get("api/dashboard_data", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((response) => {
                console.log(response.data);
                this.infosUser = response.data.infos_user;
                this.booksUser = response.data.books_user;
            })
            .catch((error) => {
                console.error("Erreur lors de la récupération des données du tableau de bord :", error);
            });
    },
};
</script>


<style scoped>
</style>
