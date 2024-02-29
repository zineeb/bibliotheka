<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    maxStars: {
        type: Number,
        default: 5
    },
    initialRating: {
        type: Number,
        default: 0
    }
});

const rating = ref<number>(props.initialRating);
const emit = defineEmits(['update:rating']);

const setRating = (newRating: number) => {
    rating.value = newRating;
    emit('update:rating', newRating);
};
</script>


<template>
    <div class="flex items-center">
        <template v-for="star in props.maxStars" :key="star">
            <svg
                @click="setRating(star)"
                class="cursor-pointer w-6 h-6"
                fill="currentColor"
                :class="rating.valueOf() >= star ? 'text-yellow-400' : 'text-gray-300'"
                viewBox="0 0 20 20"
            >
                <path d="M9.049 2.927c.396-1.068 1.954-1.068 2.35 0l1.615 4.362h4.686c1.105 0 1.565 1.42.754 2.065l-3.806 3.04 1.423 4.848c.218.743-.602 1.36-1.256.94L10 12.34l-4.015 2.842c-.654.42-1.474-.197-1.256-.94l1.423-4.848-3.806-3.04c-.81-.645-.35-2.065.754-2.065h4.686L9.049 2.927z" />
            </svg>
        </template>
    </div>
</template>

