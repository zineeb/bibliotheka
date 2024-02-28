<script setup lang="ts">
import {ref, defineProps, defineEmits} from 'vue';
import StarRating from './StarRating.vue';
import axios from "axios";
import {useAuthStore} from "@/store";
import {useRouter} from "vue-router";

const props = defineProps({
    book: Object
});

const authStore = useAuthStore();
const router = useRouter();

const readingStatus = ref('');
const rating = ref(0);
const currentPage = ref<number|null>(null);
const comment = ref('');

const emit = defineEmits(['completeStepTwo']);

const updateRating = (newRating: number) => {
    console.log('New rating:', newRating);
    rating.value = newRating;
};

const submitBook = async () => {
    const bookData = {
        book: props.book,
        rating: rating.value,
        readingStatus: readingStatus.value,
        comment: comment.value,
        currentPage: currentPage.value
    };

    try {
        const response = await axios.post('/api/addBook', bookData, {
            headers: {
                'Authorization': `Bearer ${authStore.token}`,
                'Content-Type': 'application/json'
            },
        });

        if (response.status === 200) {
            router.push({ name: 'Dashboard' });
        }
    } catch (error) {
        console.error('Error submitting book:', error);
    }
};
</script>

<template>
    <h2>Step 2: Confirm Book Details</h2>
    <div class="flex flex-col items-center p-8 bg-white rounded text-sm">
        <div v-if="props.book" class="w-full">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ props.book.volumeInfo.title }}</h3>
            <div class="mb-2 text-gray-600">
                {{ props.book.volumeInfo.authors.join(', ') }} | {{ props.book.volumeInfo.publishedDate }}
            </div>
            <div class="italic mb-4 text-gray-600">
                Catégorie : {{ props.book.volumeInfo.categories[0] }}
            </div>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-4 md:mb-0">
                    <img :src="props.book.volumeInfo.imageLinks.thumbnail" alt="Cover image" class="w-full"/>
                </div>
                <div class="md:w-2/3 ml-4">
                    <p class="text-gray-600 text-justify">{{ props.book.volumeInfo.description }}</p>
                    <div class="mt-4">
                        <label for="status" class="block text-gray-700 font-bold mb-2">Status de lecture:</label>
                        <select id="status" v-model="readingStatus" class="w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500">
                            <option value="to_read">A lire</option>
                            <option value="reading">En cours de lecture</option>
                            <option value="read">Lu</option>
                        </select>
                    </div>
                    <div v-if="readingStatus === 'reading'" class="mt-4">
                        <label for="currentPage" class="block text-gray-700 font-bold mb-2">Page actuelle:</label>
                        <input type="number" id="currentPage" v-model="currentPage" :max="props.book.volumeInfo.pageCount"
                               class="w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"
                               placeholder="Numéro de la page actuelle">
                    </div>
                    <div class="mt-4">
                        <label for="rating" class="block text-gray-700 font-bold mb-2">Note:</label>
                        <StarRating :initialRating="rating" @update:rating="updateRating" />
                    </div>
                    <div class="mt-4">
                        <label for="comment" class="block text-gray-700 font-bold mb-2">Commentaire:</label>
                        <textarea id="comment" v-model="comment" placeholder="Entrez votre commentaire"
                                  class="w-full p-3 bg-transparent border-b-2 border-gray-300 placeholder-gray-500 text-gray-600 focus:outline-none focus:border-blue-500"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <button class="mt-4 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition self-end" @click="submitBook">
            Ajouter le livre
        </button>
    </div>
</template>



