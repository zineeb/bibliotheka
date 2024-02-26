<script setup lang="ts">
import {ref, onMounted} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";
import {useRouter} from "vue-router";

interface Book {
    id: number;
    title: string;
    author: string;
    description: string;
    publication_date: string;
    cover_image: string;
    status: string;
    rating: number;
    review: string;
}

const authStore = useAuthStore();
const router = useRouter();
const books = ref<Book[]>([]);

const addBooks = () => {
    router.push({name: 'AddBook'});
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/dashboard_data', {
            headers: {
                'Authorization': `Bearer ${authStore.token}`
            }
        });
        books.value = response.data.books_user;
    } catch (error) {
        console.error('Error fetching books:', error);
    }
});
</script>

<template>
    <div class="pt-24 min-h-screen bg-gray-100 -4">
        <div class="mx-auto max-w-7xl">
            <!-- PREMIERE PARTIE : titre et bouton ajout -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <button @click="addBooks" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Add
                    Books
                </button>
            </div>
            <hr class="my-4 border-t-2 border-gray-200"/>

            <!-- DEUXIEME PARTIE : filtre d'affichage des livres -->
            <div class="mb-4">
                <ul class="flex space-x-4">
                    <li class="text-gray-700 cursor-pointer">All</li>
                    <li class="text-gray-700 cursor-pointer">Currently Reading</li>
                    <li class="text-gray-700 cursor-pointer">To Read</li>
                    <li class="text-gray-700 cursor-pointer">Read</li>
                </ul>
            </div>

            <!-- TROISIEME PARTIE : affichage des livres -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div v-for="book in books" :key="book.id" class="bg-white rounded-lg shadow p-4 flex">
                    <img :src="book.cover_image" alt="Book cover" class="w-20 h-20 rounded mr-4"/>
                    <div>
                        <h2 class="font-bold">{{ book.title }}</h2>
                        <p class="text-sm">{{ book.author }} | {{ book.publication_date }}</p>
                        <p class="text-sm truncate">{{ book.description }}</p>
                        <p class="text-sm">{{ book.status }}</p>
                        <p class="text-sm">
                            <span class="text-yellow-400">★</span> x {{ book.rating }}
                        </p>
                        <p class="text-sm">{{ book.review }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>






