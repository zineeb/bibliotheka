<script setup lang="ts">
import {ref, onMounted, watch, watchEffect} from 'vue';
import axios from 'axios';
import {useAuthStore} from "@/store";
import {useRouter} from "vue-router";
import StarRating from './addBooks/StarRating.vue';

interface Book {
    id: number;
    google_books_id: number;
    title: string;
    author: string;
    description: string;
    publication_date: string;
    cover_image: string;
    status: 'to_read' | 'reading' | 'read';
    rating: number;
    review: string;
    page: number;
    editingStatus: boolean;
    editingPage: boolean;
    editingReview: boolean;
}

const authStore = useAuthStore();
const router = useRouter();

const rating = ref(0);
const books = ref<Book[]>([]);
const filteredBooks = ref<Book[]>([]);
const searchQuery = ref('');

const addBooks = () => {
    router.push({name: 'AddBook'});
}

const searchBooks = () => {
    filteredBooks.value = books.value.filter(book => book.title.toLowerCase().includes(searchQuery.value.toLowerCase()));
};

const filterBooks = (status: string) => {
    if (status === 'all') {
        filteredBooks.value = books.value;
    } else {
        filteredBooks.value = books.value.filter(book => book.status === status);
    }
};


const getStatusInFrench = (status: string): string => {
    const statusTranslations: Record<string, string> = {
        'to_read': 'À lire',
        'reading': 'En cours de lecture',
        'read': 'Lu'
    };
    return statusTranslations[status] || status;
};

const handleRatingUpdate = async (newRating: number, book: Book) => {
    book.rating = newRating;
    await updateBook(book);
};


const updateBook = async (book: Book) => {
    try {
        const response = await axios.post('/api/updateBook', book, {
            headers: {
                'Authorization': `Bearer ${authStore.token}`,
                'Content-Type': 'application/json'
            },
        });

    } catch (error) {
        console.error('Error editing book:', error);
    }
};

const deleteBook = async (book: Book) => {
    try {
        const response = await axios.post('/api/deletebook', { google_books_id: book.google_books_id }, {
            headers: {
                'Authorization': `Bearer ${authStore.token}`,
                'Content-Type': 'application/json'
            },
        });

        if (response.status === 200) {
            books.value = books.value.filter(b => b.id !== book.id);
        }
    } catch (error) {
        console.error('Error deleting book:', error);
    }
};


onMounted(async () => {
    try {
        const response = await axios.get('/api/dashboard_data', {
            headers: {
                'Authorization': `Bearer ${authStore.token}`
            }
        });
        const initialBooks = response.data.books_user.map((book: Book) => ({
            ...book,
            editingStatus: false,
            editingPage: false,
            editingReview: false
        }));
        books.value = initialBooks;
        filteredBooks.value = initialBooks; // Ajout de cette ligne pour initialiser filteredBooks
    } catch (error) {
        console.error('Error fetching books:', error);
    }
});

watchEffect(() => {
    searchBooks();
});
</script>

<template>
    <div class="pt-24 min-h-screen bg-gray-100 -4">
        <div class="mx-auto max-w-7xl">
            <!-- PREMIERE PARTIE : titre et bouton ajout -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <button @click="addBooks"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Add
                    Books
                </button>
            </div>
            <div class="mb-4">
                <input type="text" v-model="searchQuery" placeholder="Rechercher livre..." class="px-3 py-2 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500">
            </div>
            <hr class="my-4 border-t-2 border-gray-200"/>

            <!-- DEUXIEME PARTIE : filtre d'affichage des livres -->
            <div class="mb-4">
                <ul class="flex space-x-4">
                    <li @click="filterBooks('all')" class="text-gray-700 cursor-pointer">All</li>
                    <li @click="filterBooks('reading')" class="text-gray-700 cursor-pointer">Currently Reading</li>
                    <li @click="filterBooks('to_read')" class="text-gray-700 cursor-pointer">To Read</li>
                    <li @click="filterBooks('read')" class="text-gray-700 cursor-pointer">Read</li>
                </ul>
            </div>

            <!-- TROISIEME PARTIE : affichage des livres -->
            <div class="grid grid-cols-1 gap-4">
                <div v-for="book in filteredBooks" :key="book.id" class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row overflow-hidden">
                    <img :src="book.cover_image" alt="Book cover" class="w-32 h-32 rounded md:mr-4"/>
                    <div class="md:flex md:flex-col md:justify-between flex-grow relative"> <!-- Ajout de la classe relative ici -->
                        <div>
                            <h2 class="font-bold">{{ book.title }}</h2>
                            <p class="text-sm">{{ book.author }} | {{ book.publication_date }}</p>
                            <p class="text-sm overflow-hidden line-clamp-4 text-justify">{{ book.description }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <!-- Statut de lecture modifiable -->
                            <div @click="book.editingStatus = true" v-if="!book.editingStatus">
                                {{ getStatusInFrench(book.status) }}
                            </div>
                            <select v-else v-model="book.status" @blur="book.editingStatus = false" @change="updateBook(book)">
                                <option value="to_read">A lire</option>
                                <option value="reading">En cours de lecture</option>
                                <option value="read">Lu</option>
                            </select>
                            <div v-if="book.status === 'reading'">
                                <div @click="book.editingPage = true" v-if="!book.editingPage">
                                    Page actuelle : {{ book.page || 'Non spécifié' }}
                                </div>
                                <input v-else type="number" v-model="book.page" @blur="book.editingPage = false" @change="updateBook(book)"/>
                            </div>
                            <div @click="book.editingReview = true" v-if="!book.editingReview">
                                {{ book.review || 'Pas de commentaire' }}
                            </div>
                            <textarea v-else v-model="book.review" @blur="book.editingReview = false" @change="updateBook(book)"></textarea>
                            <div class="mb-4">
                                <StarRating :initialRating="book.rating" @update:rating="newRating => handleRatingUpdate(newRating, book)"/>
                            </div>
                            <!-- Bouton de suppression -->
                            <button @click="deleteBook(book)" class="absolute bottom-2 right-2 bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition duration-300">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>






