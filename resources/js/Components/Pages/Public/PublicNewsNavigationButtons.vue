<template>
  <div class="hidden md:block flex py-10 px-16 justify-center">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 justify-center">
      <PublicNavLink
          @click="() => Inertia.visit('/news')"
          :active="appSettingStore.currentPage === 'news'"
          class="min-w-fit">
        News Stories
      </PublicNavLink>
      <PublicNavLink
          disabled
          @click="() => Inertia.visit('/news/categories')"
          :active="appSettingStore.currentPage === 'news.categories'"
          class="min-w-fit">
        Categories
      </PublicNavLink>
      <PublicNavLink
          disabled
          @click="() => Inertia.visit('/news/cities')"
          :active="appSettingStore.currentPage === 'news.cities'"
          class="min-w-fit">
        Cities
      </PublicNavLink>
      <PublicNavLink
          @click="() => Inertia.visit('/news-districts')"
          :active="appSettingStore.currentPage === 'newsDistricts.index'"
          class="min-w-fit">
        Districts
      </PublicNavLink>
      <PublicNavLink
          @click="() => Inertia.visit('/news/reporters')"
          :active="appSettingStore.currentPage === 'news.reporters'"
          class="min-w-fit">
        Reporters
      </PublicNavLink>
    </div>
  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Button from '@/Jetstream/Button.vue'
import { computed } from 'vue'
import PublicNavLink from '@/Components/Global/Buttons/PublicNavLink.vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  href: String,
  active: Boolean,
});

const classes = computed(() => {
  return props.active
  ? 'flex justify-center items-center p-2 bg-blue-500 rounded leading-5 hover:bg-indigo-400 transition'
      : 'flex justify-center items-center p-2 bg-indigo-400 hover:bg-indigo-400 rounded leading-5 transition';
  // : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-blue-700 hover:border-blue-600 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
});

</script>

<style scoped>

</style>