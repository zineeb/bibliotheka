<script setup lang="ts">
import {ref, defineEmits} from "vue";
import axios from "axios";
import {useAuthStore} from "@/store";

interface Book {
    id: string;
    volumeInfo: {
        title: string;
        authors: string[];
    };
};

const searchQuery = ref('');
const searchResults = ref<Book[]>([]);

const authStore = useAuthStore();
const emit = defineEmits(['bookSelected']);

const searchBooks = async () => {
    if (searchQuery.value.length < 3) {
        searchResults.value = [];
        return;
    }

    try {
        const response = await axios.get('/api/search-books', {
            headers: {
                'Authorization': `Bearer ${authStore.token}`
            },
            params: {
                query: searchQuery.value
            }
        });
        searchResults.value = response.data || [];
        console.log('searchResults : ' , searchResults.value);
    } catch (error) {
        console.error('Error searching books:', error);
        searchResults.value = [];
    }
};

const selectBook = (book: Book) => {
    emit('bookSelected', book);
};

</script>

<template>
    <div>
        <h2>Étape 1 : Recherche du livre</h2>
        <input type="text" v-model="searchQuery" @input="searchBooks" placeholder="Commencer à taper pour rechercher un livre ..." class="border p-2">
        <ul v-if="searchResults.length > 0" class="border -2">
            <li v-for="book in searchResults" :key="book.id" @click="selectBook(book)" class="cursor-pointer hover:bg-blue-100 p--2">
                {{ book.volumeInfo.title }} par {{ book.volumeInfo.authors.join(', ') }}
            </li>
        </ul>
    </div>
</template>
