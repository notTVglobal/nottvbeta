<template>
  <div class="p-6">

    <div class="flex flex-col lg:flex-row w-full justify-between items-center mb-6 lg:mb-0">
      <!-- Paginator -->
      <!--                    <Pagination :data="teams" class=""/>-->

      <h1 class="text-3xl font-semibold pb-3 text-white">Teams</h1>
      <div class="gap-x-4 mb-4">
        <input v-model="search" type="search" placeholder="Search Teams..."
               class="border px-2 rounded-lg"/>
      </div>
    </div>

    <div class="gap-4 flex flex-row flex-wrap justify-center w-full overflow-x-none">
      <div v-for="team in teams.data"
           :key="team.id"
           @click="Inertia.visit(`/teams/${team.slug}`)"
           class=" bg-gray-300 shadow-md hover:bg-yellow-400 hover:cursor-pointer flex flex-col max-w-[12rem] justify-center items-center py-4 rounded-lg">

        <div class="flex items-center justify-center min-w-[12rem] px-6 pb-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
          <!--                                                <img :src="`/storage/${team.logo}`" class="rounded-full h-20 w-20 object-cover">-->
          <!--                                                <img :src="'/storage/images/' + team.logo" class="rounded-full h-20 w-20 object-cover">-->
          <SingleImage :image="team.image"
                       :alt="'team logo rounded full'"
                       :key="team.image.id"
                       :class="'rounded-full h-20 w-20 object-cover'"/>

        </div>
        <div class="max-w-[10rem] px-6 font-medium text-gray-900 dark:text-white break-words text-center ">
          <Link :href="`/teams/${team.slug}`" class="font-semibold text-blue-800 hover:text-blue-600">{{
              team.name
            }}
          </Link>
        </div>
      </div>

    </div>

    <!-- Paginator -->
    <Pagination :data="teams" class=""/>

  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import Pagination from '@/Components/Global/Paginators/Pagination'

let props = defineProps({
  teams: Object,
  filters: Object
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/teams', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))
</script>