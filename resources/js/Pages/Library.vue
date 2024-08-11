<template>
  <Head title="My Library"/>

  <div class="place-self-center flex flex-col gap-y-6 text-center">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-8 rounded-lg shadow-lg">
      <header class="flex justify-center my-6">
        <div>
          <h1 class="text-4xl font-bold pb-4">📚 My Library</h1>
          <p class="text-lg font-medium text-gray-700 dark:text-gray-300">
            Your personalized collection of content is coming soon!
          </p>
        </div>
      </header>

      <div class="flex flex-col items-center mb-8">
        <input v-model="search" type="search" placeholder="Search saved content..."
               class="border-2 border-orange-400 px-4 py-2 rounded-full text-black dark:text-gray-900 w-2/3 focus:ring-2 focus:ring-orange-300"/>
      </div>

      <div class="mb-6">
        <div class="p-4 bg-yellow-100 text-yellow-900 rounded-lg">
          <span class="font-semibold">🚧 Under Construction</span>
          <p class="mt-2">
            We're working hard to bring you this exciting new feature. Soon, you'll be able to see all your favorite
            shows, movies, live events, and more in one place.
          </p>
        </div>
      </div>

      <div class="flex justify-center">
        <div class="bg-orange-300 text-black px-6 py-4 rounded-lg">
          <span class="font-semibold">✨ Display library items here.</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('library')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  can: Object,
})

</script>

