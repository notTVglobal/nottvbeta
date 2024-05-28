<template>
  <div class="py-10 px-4 md:px-16 flex justify-center">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 py-10 px-4 md:px-16 max-w-7xl mx-auto">
      <PublicNavLink
          @click.prevent="router.visit('/news')"
          :active="appSettingStore.currentPage === 'news.index'"
          class="min-w-full text-center">
        News Stories
      </PublicNavLink>
      <PublicNavLink
          @click.prevent="router.visit('/news/categories')"
          :active="appSettingStore.currentPage.includes('news.category.index')"
          class="min-w-full text-center">
        Categories
      </PublicNavLink>
      <PublicNavLink
          @click.prevent="router.visit('/news/city')"
          :active="appSettingStore.currentPage.includes('news.city.index')"
          class="min-w-full text-center">
        Cities
      </PublicNavLink>
      <PublicNavLink
          @click.prevent="router.visit('/news-districts')"
          :active="appSettingStore.currentPage.includes('newsDistricts.index')"
          class="min-w-full text-center">
        Districts
      </PublicNavLink>
      <PublicNavLink
          @click.prevent="router.visit('/news/reporters')"
          :active="appSettingStore.currentPage.includes('news.reporters.index')"
          class="min-w-full text-center">
        Reporters
      </PublicNavLink>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import PublicNavLink from '@/Components/Global/Buttons/PublicNavLink.vue'

const appSettingStore = useAppSettingStore()
</script>

<style scoped>
/* You can add any additional custom styles here */
</style>
