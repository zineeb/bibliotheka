<template>
  <div class="carousel">
    <button @click="prevSlide">Précédent</button>
    <div class="slide-container">
      <transition-group name="slide" tag="div">
        <div :key="currentIndex" class="slide" v-for="(slide, index) in slides" v-show="index === currentIndex">
          <img :src="slide.image" :alt="slide.caption">
          <p>{{ slide.caption }}</p>
        </div>
      </transition-group>
    </div>
    <button @click="nextSlide">Suivant</button>
  </div>
</template>

<script>
export default {
  name: "Carousel",
  data() {
    return {
      currentIndex: 0,
      slides: [
        {
          image: '../public/images/HP1.png',
          caption: 'Légende de l\'image 1'
        },
        {
          image: '../public/images/HP1.png',
          caption: 'Légende de l\'image 2'
        },
        {
          image: '../public/images/HP1.png',
          caption: 'Légende de l\'image 3'
        }
      ]
    };
  },
  methods: {
    prevSlide() {
      this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
    },
    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.slides.length;
    }
  }
};
</script>