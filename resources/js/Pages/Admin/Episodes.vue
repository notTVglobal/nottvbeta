<template>
  <Head title="Admin/Episodes"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Episodes</AdminHeader>


      <div class="flex flex-row justify-end gap-x-4">
        <input v-model="search" type="search" placeholder="Search..." class="text-black border px-2 rounded-lg"/>
      </div>


      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


              <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200">
                  <!-- Paginator -->
                  <Pagination :data="episodes" class="mb-6"/>
                  <div
                      class="relative overflow-x-auto shadow-md sm:rounded-lg"
                  >


                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400 overflow-x-auto"
                    >
                      <thead
                          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                      >
                      <tr>
                        <th scope="col" class="min-w-[8rem] px-6 py-3">

                        </th>
                        <th scope="col" class="px-6 py-3">
                          Episode Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                          Show
                        </th>
                        <th scope="col" class="px-6 py-3">
                          ULID
                        </th>
                        <th scope="col" class="px-6 py-3">
                          Team
                        </th>
                        <th scope="col" class="px-6 py-3">
                          Status
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr
                          v-for="episode in episodes.data"
                          :key="episode.id"
                          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                      >
                        <th
                            scope="row"
                            class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >

                          <Link v-if="episode && episode.showSlug" :href="`/shows/${episode.showSlug}/episode/${episode.slug}`" class="">
                            <SingleImage :image="episode.image" :alt="'show cover'"
                                         class="rounded-full h-20 w-20 object-cover"/>
                          </Link>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link v-if="episode && episode.showSlug" :href="`/shows/${episode.showSlug}/episode/${episode.slug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ episode.name }}
                          </Link>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link v-if="episode && episode.showSlug" :href="`/shows/${episode.showSlug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ episode.show }}
                          </Link>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ episode.ulid }}
                        </th>
                        <td
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link v-if="episode && episode.teamSlug" :href="`/teams/${episode.teamSlug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ episode.teamName }}
                          </Link>
                        </td>
                        <td
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ episode.status }}
                        </td>
                      </tr>
                      </tbody>
                    </table>
                    <!-- Paginator -->
                    <Pagination :data="episodes" class="pb-6"/>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { onBeforeMount, onMounted, ref, watch } from "vue"
import throttle from "lodash/throttle"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import AdminHeader from "@/Components/Pages/Admin/AdminHeader"
import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

const appSettingStore = useAppSettingStore()

let props = defineProps({
  episodes: Object,
  filters: Object,
  // can: Object,
});

let search = ref(props.filters.search)

appSettingStore.currentPage = 'adminEpisodes'
appSettingStore.showFlashMessage = true;

watch(search, throttle(function (value) {
  router.get('/admin/episodes', {search: value}, {
    preserveState: true,
    replace: true
  });
}, 300));

</script>


