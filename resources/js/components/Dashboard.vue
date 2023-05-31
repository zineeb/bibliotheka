<template>
    <!-- Main container for the dashboard -->
    <div class="dashboard">
        <!-- User info container -->
        <div class="user-info">
            <!-- User's profile image -->
            <h2>Informations de l'utilisateur : </h2>
            <div class="info-container">
                <img :src="infosUser[0].profile_image" alt="Image de profil">
                <!-- List of user's details -->
                <div class="mini-info-container">
                    <ul>
                        <li>Nom : {{ infosUser[0].name }}</li>
                        <li>Email : {{ infosUser[0].email }}</li>
                    </ul>
                    <a :href="'/user-informations/' + infosUser[0].id">Voir et modifier mes informations</a>
                    <!-- Link to view and edit user's information -->
                </div>

            </div>
        </div>

        <!-- Buttons to open modals for adding categories and books -->
        <div class="open-modal-book">
            <button @click="showModalCategory = true">Ajouter une Categorie
                <i class="fa-solid fa-chevron-right"/>
            </button>
            <button @click="showModalBook = true">Ajouter un Livre
                <i class="fa-solid fa-chevron-right"/>
            </button>
        </div>

        <!-- Books container -->
        <div class="books">
            <h2>Livres : </h2>
            <div class="gallery-books">

                <!-- List of user's books -->
                <!--<template v-for="(books, status) in booksByStatus">
                    <h2>{{ status }}</h2>
                    <div class="book-list" v-for="book in books" :key="book.id">
                        <img :src="book.cover_image" alt="Cover image" width="100" @click="openBookInfoModal(book)">
                        <div>{{ book.title }}</div>
                    </div> 
                    <br>
                </template>-->
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
                <div class="livre-test">
                    <h2>Livre test</h2>
                    <img src="../../../public/images/HP1.png" alt="hp" >
                </div>
            </div>
        </div>


        <!-- Modal for adding a category -->
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

        <!-- Modal for adding a book -->
        <modal v-if="showModalBook" @close="showModalBook = false">
            <template v-slot:header>
                <h3>Ajout Livre</h3>
            </template>
            <template v-slot:body>
                <!-- Inputs for entering book's title, author, and ISBN -->
                <p>Titre : *</p>
                <input type="text" v-model="modalTitleInput" placeholder="Titre livre ..."/><br>
                <p>Auteur : *</p>
                <input type="text" v-model="modalAuthorInput" placeholder="Auteur"/><br>
            </template>
            <template v-slot:footer>
                <button @click="submitModalBook">Envoyer</button>
            </template>
        </modal>

        <!-- Modal for displaying book information -->
        <modal v-if="showModalBookInfo" @close="showModalBookInfo = false">
            <template v-slot:header>
                <h3>Informations du livre</h3>
            </template>
            <template v-slot:body>
                <!-- Container to display book information and options -->
                <div v-if="bookInfo">
                    <!-- Display book details -->
                    <img :src="bookInfo.coverimage" alt="Couverture du livre"/>
                    <h4>{{ bookInfo.title }}</h4>
                    <p>Auteur : {{ bookInfo.author }}</p>
                    <p>Date de publication : {{ bookInfo.publication_date }}</p>
                    <p>Description : {{ bookInfo.description }}</p>
                    <p>Maison d'édition : {{ bookInfo.publisher }}</p>
                    <p>Nombre de page : {{ bookInfo.page_count }}</p>
                    <p>Genre : {{ bookInfo.genre }}</p>

                    <!-- Hidden inputs to store ISBN and Google Books ID -->
                    <input type="hidden" v-model="bookInfo.isbn"/>
                    <input type="hidden" v-model="bookInfo.google_books_id"/>

                    <!-- Container for displaying recent reviews -->
                    <div v-if="reviews.length > 0">
                        <h4>Avis récents :</h4>
                        <ul>
                            <li v-for="(review, index) in reviews" :key="index">
                                Note : {{ ratings[index] }} - {{ review }}
                            </li>
                        </ul>
                    </div>

                    <!-- Dropdown to select reading status -->
                    <label for="reading-status">État de lecture :</label>
                    <select v-model="readingStatus" @change="handleReadingStatusChange" id="reading-status">
                        <option value="to_read">À lire</option>
                        <option value="reading">En cours de lecture</option>
                        <option value="read">Lu</option>
                    </select>

                    <!-- Input for entering a category -->
                    <label for="category">Catégorie :</label>
                    <input type="text" v-model="category" id="category" placeholder="Catégorie"/>

                    <!-- Container for rating and review when reading status is 'read' -->
                    <div v-if="readingStatus === 'read'">
                        <label for="rating">Note (1-5) :</label>
                        <select v-model="rating" id="rating">
                            <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                        </select>

                        <label for="review">Avis :</label>
                        <input type="text" v-model="review" id="review" placeholder="Votre avis sur le livre"/>
                    </div>

                    <!-- Input for entering current page when reading status is 'reading' -->
                    <div v-if="readingStatus === 'reading'">
                        <label for="current_page">Page actuelle :</label>
                        <input type="number" v-model="current_page" id="current_page" min="1" :max="bookInfo.page_count"
                               placeholder="Page actuelle"/>
                    </div>

                    <!-- Button to add the book -->
                    <button @click="addBooks">Ajouter</button>
                </div>
            </template>
            <template v-slot:footer>
                <button @click="showModalBookInfo = false">Fermer</button>
            </template>
        </modal>

        <modal v-if="showBookInfoModal" @close="showBookInfoModal = false">
            <template v-slot:header>
                <h3>Informations du livre</h3>
            </template>
            <template v-slot:body>
                <img :src="bookInfo.cover_image" alt="Couverture du livre"/>
                <h4>{{ bookInfo.title }}</h4>
                <p>Auteur : {{ bookInfo.author }}</p>
                <p>Date de publication : {{ bookInfo.publication_date }}</p>
                <p>Description : {{ bookInfo.description }}</p>
                <p>Maison d'édition : {{ bookInfo.publisher }}</p>
                <p>Nombre de pages : {{ bookInfo.page }}</p>
                <p>Catégorie : {{ bookInfo.category }}</p>

                <input type="hidden" v-model="bookInfo.google_books_id"/>

                <label for="status">État de lecture :</label>
                <select v-model="bookInfo.status" id="status">
                    <option value="to_read">À lire</option>
                    <option value="reading">En cours de lecture</option>
                    <option value="read">Lu</option>
                </select>

                <div v-if="bookInfo.status === 'reading'">
                    <label for="current_page">Page actuelle :</label>
                    <input type="number" v-model="bookInfo.current_page" id="current_page" min="1" :max="bookInfo.page_count"
                           placeholder="Page actuelle"/>
                </div>

                <div v-if="bookInfo.status === 'read'">
                    <label for="rating">Note (1-5) :</label>
                    <select v-model="bookInfo.rating" id="rating">
                        <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                    </select>

                    <label for="review">Avis :</label>
                    <input type="text" v-model="bookInfo.review" id="review" placeholder="Votre avis sur le livre"/>
                </div>
            </template>
            <template v-slot:footer>
                <button @click="updateBookInfo">Enregistrer</button>
                <button @click="showBookInfoModal = false">Fermer</button>
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
            showBookInfoModal: false,
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
            // Prepare the data to be sent
            const data = {
                category: this.modalCategoryInput,
            };

            // Retrieve the user's JWT token
            const token = localStorage.getItem("token");

            // Make the POST request to the API
            axios
                .post("api/addcategory", data, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    // Manage the API response
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
                description: this.bookInfo.description,
                publisher: this.bookInfo.publisher,
                publication_date: this.bookInfo.publication_date,
                page_count: this.bookInfo.page_count,
                genre: this.bookInfo.genre,
                cover_image: this.bookInfo.coverimage,
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
                        this.refreshDashboardData();
                    } else {
                        // Handle errors, for example display an error message
                    }
                })
                .catch(error => {
                    console.error("Erreur lors de la mise à jour des informations de l'utilisateur :", error);
                });
        },
        translateStatus(status) {
            switch (status) {
                case 'read':
                    return 'Lu';
                case 'to_read':
                    return 'A lire';
                case 'reading':
                    return 'En cours de lecture';
                default:
                    return status;
            }
        },
        refreshDashboardData() {
            const token = localStorage.getItem("token");
            axios
                .get("api/dashboard_data", {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    this.infosUser = response.data.infos_user;
                    this.booksByStatus = this.groupBooksByStatus(response.data.books_user);
                })
                .catch((error) => {
                    console.error("Erreur lors de la récupération des données du tableau de bord :", error);
                });
        },
        groupBooksByStatus(books) {
            return books.reduce((groups, book) => {
                const status = this.translateStatus(book.status);
                if (!groups[status]) {
                    groups[status] = [];
                }
                console.log(book);
                groups[status].push(book);
                return groups;
            }, {});
        },
        openBookInfoModal(book) {
            this.bookInfo = book;
            this.showBookInfoModal = true;
        },
        async updateBookInfo() {
            const token = localStorage.getItem("token");
            if (!this.bookInfo) return;
            axios.post("/api/updateBook", {
                google_books_id: this.bookInfo.google_books_id,
                status: this.bookInfo.status,
                rating: this.bookInfo.rating,
                review: this.bookInfo.review,
                current_page: this.bookInfo.current_page,
            }, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
                .then(response => {
                    if (response.data.status === 'success') {
                        this.showBookInfoModal = false;
                        this.refreshDashboardData();
                    } else {
                        // Handle errors, for example display an error message
                    }
                })
                .catch(error => {
                    console.error(error);
                });


        },
    },
    created() {
        this.refreshDashboardData();
    },
};
</script>


<style scoped>
</style>
