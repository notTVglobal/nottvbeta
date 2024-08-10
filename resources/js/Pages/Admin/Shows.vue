<template>
  <Head title="Admin/Shows"/>

  <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-white p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Shows</AdminHeader>

      <div class="flex justify-end mb-6">
        <div>
          <input v-model="search" type="search" placeholder="Search..." class="text-black border px-2 rounded-lg mb-6"/>
        </div>
      </div>


      <div>
        <!-- Paginator -->
        <Pagination :data="shows" class=""/>
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
                      <tr>
                        <th scope="col" class="min-w-[8rem] px-6 py-3">
                          Poster
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('name')">
                          Show Name <span v-if="sortBy === 'name'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('showRunnerName')">
                          Show Runner <span v-if="sortBy === 'showRunnerName'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('teamName')">
                          Team <span v-if="sortBy === 'teamName'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('totalEpisodes')">
                          # of Episodes <span v-if="sortBy === 'totalEpisodes'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="toggleSort('status')">
                          Status <span v-if="sortBy === 'status'">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        </th>
                        <th v-if="props.can.viewCreator" scope="col" class="px-6 py-3">
                          <!--Manage/Edit-->
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr
                          v-for="show in props.shows.data"
                          :key="show.id"
                          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                      >
                        <th
                            scope="row"
                            class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <!--                                                    <img :src="`/storage/images/${show.poster}`" class="rounded-full h-20 w-20 object-cover">-->
                          <Link :href="`/shows/${show.slug}`" class="text-blue-800 hover:text-blue-600">
                            <SingleImage :image="show.image" :alt="'show cover'"
                                         :class="'rounded-full h-20 w-20 object-cover'"/>
                          </Link>


                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 text-xl text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link :href="`/shows/${show.slug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ show.name }}
                          </Link>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ show.showRunnerName }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link :href="`/teams/${show.teamSlug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ show.teamName }}
                          </Link>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ show.totalEpisodes }}
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 whitespace-nowrap"
                        >
                          <div v-if="show.statusId===1" class="font-semibold text-pink-500">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===2" class="font-semibold text-green-600">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===3" class="font-medium text-orange-400">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===4" class="text-gray-500">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===5"
                               class="font-semibold py-1 px-2 w-fit rounded-md bg-black text-gray-50">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===6" class="font-italic text-gray-500">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===7" class="font-medium font-italic text-red-600">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===8" class="font-semibold font-italic text-gray-500">
                            {{ show.status }}
                          </div>
                          <div v-if="show.statusId===9" class="font-semibold text-green-600">
                            {{ show.status }}
                          </div>
                        </th>
                        <td class="px-6 py-4 space-x-2">
                          <Link v-if="show.can.viewShow" :href="`/shows/${show.slug}/manage`">
                            <button
                                class="px-4 py-2 mb-2 text-white bg-purple-600 hover:bg-purple-500 rounded-lg"
                            >Manage
                            </button>
                          </Link>
                          <Link v-if="show.can.editShow" :href="`/shows/${show.slug}/edit`">
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
      <Pagination :data="shows" class="pb-6"/>


      <div class="flex items-center">
        <!--               <Link :href="`/shows/${show.id}`" class="text-indigo-600 hover:text-indigo-900">Link to a show</Link>-->

      </div>
    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, watch } from "vue"
import throttle from "lodash/throttle"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import AdminHeader from "@/Components/Pages/Admin/AdminHeader.vue"
import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

usePageSetup('adminShows')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  shows: Object,
  teamName: String,
  poster: String,
  filters: Object,
  can: Object,
});

let search = ref(props.filters.search);
let sortBy = ref(props.filters.sort_by || 'name');
let sortDirection = ref(props.filters.sort_direction || 'asc');

watch([search, sortBy, sortDirection], throttle(function () {
  router.get('/admin/shows', {
    search: search.value,
    sort_by: sortBy.value,
    sort_direction: sortDirection.value,
  }, {
    preserveState: true,
    replace: true
  });
}, 300));

function toggleSort(column) {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = column;
    sortDirection.value = 'asc';
  }
}

</script>


