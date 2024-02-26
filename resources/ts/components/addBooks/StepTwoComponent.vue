<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    book: Object
});

// Définir les événements que ce composant peut émettre
const emit = defineEmits(['completeStepTwo']);

const readingStatus = ref('');
const rating = ref(1);
const comment = ref('');

const submitBook = () => {
    // Vous pouvez valider les données ici si nécessaire
    if (!readingStatus.value || rating.value < 1 || rating.value > 5) {
        alert('Please fill out all fields correctly.');
        return;
    }

    // Préparer les données du livre pour émission ou pour envoi API
    const bookData = {
        ...props.book, // les détails du livre provenant de l'étape précédente
        readingStatus: readingStatus.value,
        rating: rating.value,
        comment: comment.value,
    };

    emit('completeStepTwo', bookData);
};
</script>

<template>
    <div>
        <h2>Step 2: Confirm Book Details</h2>
        <div v-if="props.book">
            <h3>{{ props.book.volumeInfo.title }}</h3>
            <img :src="props.book.volumeInfo.imageLinks.thumbnail" alt="Cover image" class="thumbnail" />
            <p>{{ props.book.volumeInfo.authors.join(', ') }}</p>
            <p>{{ props.book.volumeInfo.description }}</p>

            <label for="status">Reading Status:</label>
            <select id="status" v-model="readingStatus">
                <option value="to_read">To Read</option>
                <option value="reading">Currently Reading</option>
                <option value="read">Read</option>
            </select>

            <label for="rating">Rating:</label>
            <select id="rating" v-model="rating">
                <option v-for="num in 5" :key="num" :value="num">{{ num }}</option>
            </select>

            <label for="comment">Comment:</label>
            <textarea id="comment" v-model="comment" placeholder="Enter your comment"></textarea>

            <button @click="submitBook">Add Book</button>
        </div>
    </div>
</template>
