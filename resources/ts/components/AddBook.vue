<script setup lang="ts">
import { ref, computed, defineAsyncComponent } from 'vue';

const StepOneComponent = defineAsyncComponent(() => import('./addBooks/StepOneComponent.vue'));
const StepTwoComponent = defineAsyncComponent(() => import('./addBooks/StepTwoComponent.vue'));

const currentStep = ref(1);
const selectedBook = ref(null);

const currentStepComponent = computed(() => {
    if (currentStep.value === 1) return StepOneComponent;
    if (currentStep.value === 2) return StepTwoComponent;
    return null;
});

const handleBookSelected = (book: any) => {
    selectedBook.value = book;
    nextStep();
};

const nextStep = () => {
    if (currentStep.value < 2) currentStep.value++;
};
</script>

<template>
    <div class="pt-20 min-h-screen bg-gray-100 p-4 flex">
        <!-- Sidebar pour les étapes -->
        <div class="w-1/4 flex flex-col items-center pt-5">
            <div v-for="step in steps" :key="step.id" class="my-2 text-center">
                <div class="relative flex items-center justify-center">
                    <div :class="step.id === currentStep ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-300">
                        {{ step.id }}
                    </div>
                    <div v-if="step.id < steps.length" class="w-0.5 bg-gray-300 h-8 absolute left-1/2 bottom-0 z-0"></div>
                </div>
                <div class="text-sm mt-2">{{ step.name }}</div>
            </div>
        </div>

        <div class="w-3/4">
            <component :is="currentStepComponent" @bookSelected="handleBookSelected" :book="selectedBook" />
        </div>
    </div>
</template>

