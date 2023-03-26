<template>
    <div>
        <app-header></app-header>
        <div class="main-content">
            <div class="banner">
                <h1>Bibliotheka</h1>
            </div>
            <div class="presentation">
                <img src="#" alt="photo_zineb">
                <hr>
                <h2>Présentation du proket</h2>
                <!-- Ajouter contenu de présentation -->
            </div>
        </div>
    </div>
</template>

<script>
import header from "@/components/header.vue";

export default {
    components : {
        appHeader : header,
    },
};
</script>

<style scoped>

</style>
