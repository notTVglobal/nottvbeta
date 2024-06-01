<template>
  <div class="py-2 xl:py-10 px-4 md:px-16 flex justify-center">
    <div class="block xl:hidden relative w-full">
      <button
          @click="toggleDropdown"
          class="bg-gray-800 text-white px-4 py-4 rounded-md w-full hover:bg-gray-700 hover:border-indigo-400">
        News Menu
      </button>
      <div v-if="dropdownOpen" class="absolute left-0 mt-2 w-full bg-gray-800 rounded-md shadow-lg z-20">
        <div class="py-1 flex flex-col space-y-2">
          <PublicNavLink
              v-for="link in links"
              :key="link.route"
              @click.prevent="navigateTo(link.route)"
              :active="appSettingStore.currentPage.includes(link.activeCheck)"
              class="block px-4 py-2 text-white w-full text-center">
            {{ link.name }}
          </PublicNavLink>
        </div>
      </div>
    </div>
    <div class="hidden xl:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 py-10 px-4 md:px-16 max-w-7xl mx-auto w-full">
      <PublicNavLink
          v-for="link in links"
          :key="link.route"
          @click.prevent="navigateTo(link.route)"
          :active="appSettingStore.currentPage.includes(link.activeCheck)"
          class="min-w-full text-center text-white">
        {{ link.name }}
      </PublicNavLink>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import PublicNavLink from '@/Components/Global/Buttons/PublicNavLink.vue'

const appSettingStore = useAppSettingStore()
const dropdownOpen = ref(false)

const links = [
  { name: 'News Stories', route: '/news', activeCheck: 'news.index' },
  { name: 'Categories', route: '/news/categories', activeCheck: 'news.category.index' },
  { name: 'Cities', route: '/news/city', activeCheck: 'news.city.index' },
  { name: 'Districts', route: '/news-districts', activeCheck: 'newsDistricts.index' },
  { name: 'Reporters', route: '/news/reporters', activeCheck: 'news.reporters.index' },
]

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const navigateTo = (route) => {
  router.visit(route)
  dropdownOpen.value = false
}
</script>

<style scoped>
/* Add any additional custom styles here */
</style>
