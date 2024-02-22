<script setup lang="ts">
import {ref, onMounted, markRaw} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";
import {useRoute, useRouter} from "vue-router";
import Modal from "@/components/Modal.vue";

interface User {
    id: number | null;
    name: string;
    email: string;
    profileImage?: string;
}

interface Book {
    id?: number;
    google_books_id?: string;
    title: string;
    author: string;
    description?: string;
    publisher?: string;
    publication_date?: string;
    page_count?: number;
    genre?: string;
    coverimage?: string;
    isbn?: string;
    readingStatus?: string;
    category?: string;
    rating?: number;
    review?: string;
    current_page?: number;
}

interface Category {
    id?: number;
    name: string;
}

interface Review {
    id?: number;
    content: string;
    user: User;
    rating?: number;
}

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const infosUser = ref<User | null>(null);
const booksUser = ref([]);
const showModalCategory = ref(false);
const showModalBook = ref(false);
const showModalBookInfo = ref(false);
const showBookInfoModal = ref(false);
const modalCategoryInput = ref("");
const modalTitleInput = ref("");
const modalAuthorInput = ref("");
const modalISBNInput = ref("");
const bookInfo = ref<Book | null>(null);
const readingStatus = ref('to_read');
const category = ref("");
const rating = ref(1);
const review = ref("");
const reviews = ref([]);
const ratings = ref([]);
const id = ref(null);
const current_page = ref(1);
const selectedCategory = ref('');
const categories = ref<Category[]>([]);
const booksByStatus = ref<{ [key: string]: Book[] }>({});


const navigateToUserInformation = () => {
    if (infosUser.value?.id) {
        router.push(`/user-informations/${infosUser.value.id}`);
    }
};

const submitModalCategory =  () => {
    try {
        axios.post("api/addcategory", {
            category: modalCategoryInput.value,
        }, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });

        showModalCategory.value = false;
        modalCategoryInput.value = "";
    } catch (error) {
        console.error("Erreur lors de l'ajout de la catégorie :", error);
        showModalCategory.value = false;
        modalCategoryInput.value = "";
    }
};

const submitModalBook = async () => {
    try {
        const response = await axios.post("api/researchbook", {
            title: modalTitleInput.value,
            author: modalAuthorInput.value,
            isbn: modalISBNInput.value,
            user_id: route.params.id,
        }, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });

        if (response.data.status === "success") {
            bookInfo.value = response.data.book;
            reviews.value = response.data.review;
            ratings.value = response.data.rating;
            categories.value = response.data.categories;
            showModalBookInfo.value = true;
        } else {
            console.log(response.data.errors);
        }
        showModalBook.value = false;
        modalTitleInput.value = "";
        modalAuthorInput.value = "";
        modalISBNInput.value = "";
    } catch (error) {
        console.error("Erreur lors de la recherche du livre :", error);
        showModalBook.value = false;
        modalTitleInput.value = "";
        modalAuthorInput.value = "";
        modalISBNInput.value = "";
    }
};

const handleReadingStatusChange = () => {
    if (readingStatus.value !== 'read') {
        rating.value = 1;
        review.value = "";
    }
};

const addBooks = async () => {
    if (!bookInfo.value) return;

    try {
        const response = await axios.post("/api/addBook", {
            google_books_id: bookInfo.value.google_books_id,
            title: bookInfo.value.title,
            author: bookInfo.value.author,
            description: bookInfo.value.description,
            publisher: bookInfo.value.publisher,
            publication_date: bookInfo.value.publication_date,
            page_count: bookInfo.value.page_count,
            genre: bookInfo.value.genre,
            cover_image: bookInfo.value.coverimage,
            isbn: bookInfo.value.isbn,
            reading_status: readingStatus.value,
            category: category.value,
            rating: rating.value,
            review: review.value,
            current_page: current_page.value,
        }, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });

        if (response.data.status === "success") {
            showModalBookInfo.value = false;
            refreshDashboardData();
        }
    } catch (error) {
        console.error("Erreur lors de l'ajout du livre :", error);
    }
};

const translateStatus = (status: string) => {
    switch (status) {
        case 'read':
            return 'Lu';
        case 'to_read':
            return 'À lire';
        case 'reading':
            return 'En cours de lecture';
        default:
            return status;
    }
};

const refreshDashboardData = async () => {
    try {
        const response = await axios.get("api/dashboard_data",{
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });

        infosUser.value = response.data.infos_user;
        booksByStatus.value = groupBooksByStatus(response.data.books_user)
    } catch (error) {
        console.error("Erreur lors de la récupération des données du tableau de bord :", error);
    }
};

const groupBooksByStatus = (books: Book[]): { [key: string]: Book[] } => {
    return books.reduce((groups: { [key: string]: Book[] }, book: Book) => {
        const status = book.readingStatus ? translateStatus(book.readingStatus) : 'Inconnu'; // Utilisez 'Inconnu' ou une autre valeur par défaut comme fallback
        if (!groups[status]) {
            groups[status] = [];
        }
        groups[status].push(book);
        return groups;
    }, {});
};

const openBookInfoModal = (book: Book) => {
    bookInfo.value = book;
    readingStatus.value = book.readingStatus || 'to_read';
    current_page.value = book.current_page || 1;
    rating.value = book.rating || 0;
    review.value = book.review || '';
    showModalBookInfo.value = true;

}

const updateBookInfo = async () => {
    if (!bookInfo.value) return;

    try {
        const response = await axios.post("/api/updateBook", {
            google_books_id: bookInfo.value.google_books_id,
            status: bookInfo.value.readingStatus,
            rating: rating.value,
            review: review.value,
            current_page: current_page.value,
        }, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });

        if (response.data.status === "success") {
            showModalBookInfo.value = false;
            await refreshDashboardData();
        }
    } catch (error) {
        console.error("Erreur lors de la mise à jour des informations du livre :", error);
    }
}



onMounted(() => {
    refreshDashboardData();
});
</script>

<template>
    <div class="dashboard">
        <div class="user-info" v-if="infosUser">
            <h2>Informations de l'utilisateur : </h2>
            <div class="info-container">
                <img :src="infosUser.profileImage" alt="Image de profil" v-if="infosUser.profileImage">
                <div class="mini-info-container">
                    <ul>
                        <li>Nom : {{ infosUser.name }}</li>
                        <li>Email : {{ infosUser.email }}</li>
                    </ul>
                    <a :href="'/user-informations/' + infosUser.id"><i class="fa-solid fa-pen-to-square"></i></a>
                </div>
            </div>
        </div>


        <div class="open-modal-book">
            <button @click="showModalCategory = true">Ajouter une Categorie
                <i class="fa-solid fa-chevron-right"/>
            </button>
            <button @click="showModalBook = true">Ajouter un Livre
                <i class="fa-solid fa-chevron-right"/>
            </button>
        </div>

        <div class="books">
            <h2 class="books-title">Votre bibliothèque :</h2>
            <div class="gallery-books">
                <template v-for="(books, status) in booksByStatus">
                    <div class="status-container">
                        <h2 class="status-title">{{ status }}</h2>
                        <div class="book-list">
                            <div v-for="book in books" :key="book.id" class="book-item">
                                <div class="book-item-content">
                                    <img :src="book.coverimage" alt="Cover image" @click="openBookInfoModal(book)">
                                    <div class="book-title">{{ book.title }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>


        <Modal v-if="showModalCategory" @close="showModalCategory = false">
            <template v-slot:header>
                <h3>Ajout d'une catégorie :</h3>
            </template>
            <template v-slot:body>
                <input type="text" v-model="modalCategoryInput" placeholder="Catégorie ..."/>
            </template>
            <template v-slot:footer>
                <button @click="submitModalCategory">Envoyer</button>
            </template>
        </Modal>

        <Modal v-if="showModalBook" @close="showModalBook = false">
            <template v-slot:header>
                <h3>Ajout d'un livre</h3>
            </template>
            <template v-slot:body>
                <p>Titre : *</p>
                <input type="text" v-model="modalTitleInput" placeholder="Titre livre ..."/><br>
                <p>Auteur : *</p>
                <input type="text" v-model="modalAuthorInput" placeholder="Auteur"/><br>
            </template>
            <template v-slot:footer>
                <button @click="submitModalBook">Envoyer</button>
            </template>
        </Modal>

        <Modal v-if="showModalBookInfo" @close="showModalBookInfo = false">
            <template v-slot:header>
                <h3>Informations du livre</h3>
            </template>
            <template v-slot:body>
                <!-- Container to display book information and options -->
                <div class="container-infobook" v-if="bookInfo">
                    <!-- Display book details -->
                    <h4>{{ bookInfo.title }}</h4>
                    <img :src="bookInfo.coverimage" alt="Couverture du livre"/>

                    <div class="mini-container-info">
                        <p class="sub">Auteur : </p>
                        <p class="info">{{ bookInfo.author }}</p>

                        <p class="sub">Date de publication : </p>
                        <p class="info">{{ bookInfo.publication_date }}</p>

                        <p class="sub">Description : </p>
                        <p class="info" id="desc">{{ bookInfo.description }}</p>

                        <p class="sub">Maison d'édition : </p>
                        <p class="info">{{ bookInfo.publisher }}</p>

                        <p class="sub">Nombre de page : </p>
                        <p class="info">{{ bookInfo.page_count }}</p>

                        <p class="sub">Genre : </p>
                        <p class="info">{{ bookInfo.genre }}</p>
                    </div>

<!--                    <input type="hidden" v-model="bookInfo.isbn"/>-->
<!--                    <input type="hidden" v-model="bookInfo.google_books_id"/>-->

                    <div v-if="reviews.length > 0">
                        <h4>Avis récents :</h4>
                        <ul>
                            <li v-for="(review, index) in reviews" :key="index">
                                Note : {{ ratings[index] }} - {{ review }}
                            </li>
                        </ul>
                    </div>

                    <div class="select-infobook">
                        <label for="reading-status">État de lecture :</label>
                        <select v-model="readingStatus" @change="handleReadingStatusChange" id="reading-status">
                            <option value="to_read">À lire</option>
                            <option value="reading">En cours de lecture</option>
                            <option value="read">Lu</option>
                        </select>
                    </div>

                    <div class="select-infobook">
                        <label for="category">Catégorie :</label>
                        <select v-model="selectedCategory" id="category">
                            <option v-for="category in categories" :value="category.name">{{ category.name }}</option>
                        </select>
                    </div>


                    <div class="note" v-if="readingStatus === 'read'">
                        <label for="rating">Note (1-5) :</label><br/>
                        <select v-model="rating" id="rating">
                            <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                        </select><br/>

                        <label for="review">Avis :</label><br/>
                        <input type="text" v-model="review" id="review" placeholder="Votre avis sur le livre"/>
                    </div>

                    <div class="page-actuel" v-if="readingStatus === 'reading'">
                        <label for="current_page">Page actuelle :</label><br/>
                        <input type="number" v-model="current_page" id="current_page" min="1" :max="bookInfo.page_count"
                               placeholder="Page actuelle"/>
                    </div>

                    <div class="mini-container-btninfo">
                        <button @click="addBooks">Ajouter</button>
                    </div>
                </div>
            </template>
            <template v-slot:footer>
                <button @click="showModalBookInfo = false">Fermer</button>
            </template>
        </Modal>

        <Modal v-if="showBookInfoModal" @close="showBookInfoModal = false">
            <template v-slot:header>
                <h3>Informations du livre</h3>
            </template>
            <template v-slot:body>
                <div class="container-infobook">
                    <h4>{{ bookInfo?.title }}</h4>
                    <img :src="bookInfo?.coverimage" alt="Couverture du livre"/>
                    <div class="mini-container-info">
                        <p class="sub">Auteur : </p>
                        <p class="info">{{ bookInfo?.author }}</p>

                        <p class="sub">Date de publication : </p>
                        <p class="info">{{ bookInfo?.publication_date }}</p>

                        <p class="sub">Description : </p>
                        <p class="info" id="desc">{{ bookInfo?.description }}</p>

                        <p class="sub">Maison d'édition : </p>
                        <p class="info">{{ bookInfo?.publisher }}</p>

                        <p class="sub">Nombre de page : </p>
                        <p class="info">{{ bookInfo?.page_count }}</p>

                        <p class="sub">Genre : </p>
                        <p class="info">{{ bookInfo?.genre }}</p>
                    </div>


<!--                    <input type="hidden" v-model="bookInfo?.google_books_id"/>-->
                    <div class="select-infobook">
                        <label for="status">État de lecture :</label>
                        <select v-model="readingStatus" id="status">
                            <option value="to_read">À lire</option>
                            <option value="reading">En cours de lecture</option>
                            <option value="read">Lu</option>
                        </select>
                    </div>
                </div>


                <div class="page-actuel" v-if="readingStatus === 'reading'">
                    <label for="current_page">Page actuelle :</label><br/>
                    <input type="number" v-model="current_page" id="current_page" min="1" :max="bookInfo?.page_count"
                           placeholder="Page actuelle"/>
                </div>

                <div class="note" v-if="readingStatus === 'read'">
                    <label for="rating">Note (1-5) :</label><br/>
                    <select v-model="rating" id="rating">
                        <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                    </select><br/>

                    <label for="review">Avis :</label><br/>
                    <input type="text" v-model="review" id="review" placeholder="Votre avis sur le livre"/>
                </div>

            </template>
            <template v-slot:footer>
                <button @click="updateBookInfo">Enregistrer</button><br/>
                <button @click="showBookInfoModal = false">Fermer</button>
            </template>
        </Modal>
    </div>
</template>
