<template>
  <div>
    <h1>Movies in {{ category.name }}</h1>
    <h2>Subcategories</h2>
    <div class="subcategory-buttons">
      <button v-for="subCategory in subCategories" :key="subCategory.id" @click="filterMovies(subCategory.id)">
        {{ subCategory.name }}
      </button>
      <button @click="filterMovies(null)">All</button>
    </div>
    <h2>Movies</h2>
    <input type="text" v-model="searchQuery" placeholder="Search movies..." @input="fetchMovies(1)" />
    <ul>
      <li v-for="movie in filteredMovies" :key="movie.id">
        <h3>{{ movie.title }}</h3>
        <p>{{ movie.description }}</p>
      </li>
    </ul>
    <MoviePaginator :pagination="pagination" @page-changed="fetchMovies" />
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { useMovieStore } from '@/Stores/MovieStore';
import MoviePaginator from '@/Components/Global/Paginators/MoviePaginator.vue';

const { props } = usePage();
const category = props.value.category;
const subCategories = ref(props.value.subCategories);

const movieStore = useMovieStore();

const searchQuery = ref('');
const filteredMovies = computed(() => movieStore.filteredMovies);
const pagination = computed(() => movieStore.pagination);

const filterMovies = (subCategoryId) => {
  movieStore.filterMovies(subCategoryId);
  movieStore.fetchMovies(category.slug, 1, searchQuery.value, subCategoryId);
};

const fetchMovies = (page) => {
  movieStore.fetchMovies(category.slug, page, searchQuery.value, movieStore.selectedSubCategory);
};

// Fetch movies initially
movieStore.fetchMovies(category.slug);

watch(searchQuery, () => {
  movieStore.fetchMovies(category.slug, 1, searchQuery.value, movieStore.selectedSubCategory);
});
</script>
