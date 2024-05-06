<template>
  <div>

    <MyShowsHeader :can="can" :hasShows="shows.data && shows.data.length > 0"/>

    <div v-if="!shows.data && shows.data.length < 0"
         class="italic dark:text-gray-50 light:text-black">
      You have no shows.
    </div>

    <div v-else class="space-y-2 mb-56 lg:mb-28">
      <div
          v-for="show in shows.data"
          :key="show.id"
          class="border-b light:bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
      >
        <button
            @click="visitShowManagePage(show.slug)"
            class="w-full text-left transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-lg overflow-hidden shadow-lg bg-white dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"
        >
          <div class="flex flew-row pt-2 sm:pt-0 gap-y-2 gap-x-2 items-center text-center">
            <SingleImage :image="show.image" :alt="show.name" class="min-w-24 min-h-24 max-w-24 max-h-24"/>
            <p class="px-4 py-2 break-words font-semibold">
              {{ show.name }}
            </p>
          </div>
        </button>

      </div>
    </div>

    <MyShowsFooter :shows="shows"/>

  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import MyShowsHeader from "@/Components/Pages/Dashboard/Elements/MyShows/MyShowsHeader"
import MyShowsFooter from "@/Components/Pages/Dashboard/Elements/MyShows//MyShowsFooter"
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

defineProps({
  can: Object,
  shows: Object,
})

function visitShowManagePage(showSlug) {
  Inertia.visit(`/shows/${showSlug}/manage`)
}
</script>
