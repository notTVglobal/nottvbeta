<template>
  <Head title="Admin/Movies"/>

  <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-white p-5 mb-10">

      <Message v-if="appSettingStore.movieFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Admin Movies</AdminHeader>

      <div class="flex justify-end mb-6">
        <div>
          <input v-model="search" type="search" placeholder="Search..." class="text-black border px-2 rounded-lg mb-6"/>
        </div>
      </div>


      <div>
        <!-- Paginator -->
        <Pagination :data="movies" class=""/>
      </div>

      <div class="flex flex-col max-w-calc[100%-96rem]">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">


          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

              <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200">
                  <div
                      class="relative shadow-md sm:rounded-lg"
                  >


                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400 overflow-x-auto"
                    >
                      <thead
                          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                      >
                      <tr class="">
                        <th scope="col" class="min-w-[8rem] px-6 py-3">
                          Poster
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('name')">
                          Movie Name <span v-if="sortBy === 'name'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('team')">
                          Team <span v-if="sortBy === 'team'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('status')">
                          Status <span v-if="sortBy === 'status'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3">
                          <!--Manage/Edit-->
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr
                          v-for="movie in props.movies.data"
                          :key="movie.id"
                          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                      >
                        <th
                            scope="row"
                            class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <!--                                                    <img :src="`/storage/images/${movie.poster}`" class="rounded-full h-20 w-20 object-cover">-->
                          <Link :href="`/movie/${movie.slug}`" class="text-blue-800 hover:text-blue-600">
                            <SingleImage :image="movie.image" :alt="'movie cover'"
                                         :class="'rounded-full h-20 w-20 object-cover'"/>
                          </Link>


                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 text-xl text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link :href="`/movie/${movie.slug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ movie.name }}
                          </Link>
                          <div class="text-sm text-gray-700 mt-1">
                            {{ movie.category?.name }} &middot; {{ movie.subCategory?.name }}
                          </div>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link :href="`/teams/${movie.team.slug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ movie.team.name }}
                          </Link>
                        </th>
                        <th scope="row" class="px-6 py-4 whitespace-nowrap">
                          <div :class="statusClasses(movie.status.id)">
                            {{ movie.status.name }}
                          </div>
                        </th>
                        <td class="px-6 py-4 space-x-2">
                          <Link v-if="movie.can.editMovie" :href="`/movie/${movie.slug}/edit`">
                            <button
                                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                            >Edit
                            </button>
                          </Link>
                        </td>
                      </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>

            </div>
          </div>


        </div>
      </div>

      <!-- Paginator -->
      <Pagination :data="movies" class="pb-6"/>


      <div class="flex items-center">
        <!--               <Link :href="`/movie/${movie.id}`" class="text-indigo-600 hover:text-indigo-900">Link to a movie</Link>-->

      </div>
    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import AdminHeader from '@/Components/Pages/Admin/AdminHeader.vue'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'

usePageSetup('adminMovies')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  movies: Object,
  filters: Object,
  can: Object,
})

let search = ref(props.filters.search)
let sortBy = ref(props.filters.sort_by || 'name')
let sortDirection = ref(props.filters.sort_direction || 'asc')

function toggleSort(column) {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortDirection.value = 'asc'
  }
  router.get('/admin/movies', {
    search: search.value,
    sort_by: sortBy.value,
    sort_direction: sortDirection.value,
  }, {
    preserveState: true,
    replace: true,
  })
}

watch([search, sortBy, sortDirection], throttle(function () {
  router.get('/admin/movies', {
    search: search.value,
    sort_by: sortBy.value,
    sort_direction: sortDirection.value,
  }, {
    preserveState: true,
    replace: true,
  })
}, 300))

const statusClasses = (statusId) => {
  switch (statusId) {
    case 1:
      return 'font-semibold text-pink-500';
    case 2:
      return 'font-semibold text-green-600';
    case 3:
      return 'font-medium text-orange-400';
    case 4:
      return 'text-gray-500';
    case 5:
      return 'font-semibold py-1 px-2 w-fit rounded-md bg-black text-gray-50';
    case 6:
      return 'font-italic text-gray-500';
    case 7:
      return 'font-medium font-italic text-red-600';
    case 8:
      return 'font-semibold font-italic text-gray-500';
    case 9:
      return 'font-semibold text-green-600';
    default:
      return 'text-gray-500'; // Default styling if statusId doesn't match any case
  }
};

</script>


