<template>
    <header>
        <div class="header-container">
            <div class="icon">
                <img src="public/images/bibliotheka_icon.png" alt="bibliotheka_icon">
            </div>
            <nav>
                <a href="#" @click.prevent="goToLoginAndRegister">Connexion/Inscription</a>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
    methods : {
        goToLoginAndRegister() {
            //Naviguer vers la page de connexion
        }
    }
}
</script>

<style scoped>

</style>
