<template>
  <div>
    <!-- Paginator -->
    <Pagination :data="shows" class="mb-6"/>

    <div class="flex flex-row flex-wrap justify-center lg:justify-start pb-12">
      <div
          v-for="(show, key) in shows.data"
          :key="show.id"
          class="bg-gray-800 border-gray-700"
      >

          <div @click="Inertia.visit(`/shows/${show.slug}`)"
              class="relative min-w-[8rem] max-w-[14rem] px-6 py-4 font-medium break-words grow-0 w-full text-center text-gray-50 hover:text-blue-500 hover:cursor-pointer">
            <div v-if="show.statusId === 9" class="absolute flex justify-end w-full -mt-3 -mr-4 right-5 z-50">
              <div class="badge bg-gray-500 border-gray-500 text-gray-50 drop-shadow-lg">Creators Only</div>
            </div>
            <SingleImage :image="show.image" :poster="show.poster" :alt="'show cover'"
                         class="h-64 min-w-[8rem] mb-4 object-cover hover:opacity-75 transition ease-in-out duration-150 hover:cursor-pointer"/>
            {{ show.name }}

            <div class="uppercase tracking-wider text-yellow-700 mt-1">{{ show.categoryName[0] }}</div>
            <div class="text-sm text-yellow-500">{{ show.categorySubName[0] }}</div>
          </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import Pagination from "@/Components/Global/Paginators/Pagination"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import { Inertia } from '@inertiajs/inertia'
import { onMounted } from 'vue'

defineProps({
  shows: Object,
})


</script>
