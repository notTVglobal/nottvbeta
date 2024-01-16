<template>
  <Head title="Teams"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Teams</AdminHeader>

      <button
          @click="userStore.btnRedirect(`/teams/create`)"
          class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"
      >Create Team (needs to go to a new page: Admin/teams/create)
      </button>

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
                  <Pagination :data="teams" class="mb-6"/>
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
                          Logo
                        </th>
                        <th scope="col" class="px-6 py-3">
                          Team Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                          Team Creator
                        </th>
                        <th scope="col" class="px-6 py-3">
                          # of Members
                        </th>
                        <th scope="col" class="px-6 py-3">
                          # of Shows
                        </th>
                        <th v-if="props.can.viewCreator" scope="col" class="px-6 py-3">
                          <!--Manage/Edit-->
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr
                          v-for="team in teams.data"
                          :key="team.id"
                          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                      >
                        <th
                            scope="row"
                            class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >

                          <Link :href="`/teams/${team.slug}`" class="">
                            <SingleImage :image="team.image" :poster="team.logo" :alt="'show cover'"
                                         class="rounded-full h-20 w-20 object-cover"/>
                          </Link>
                          <span
                              :class="`badge ${getBadgeColor(team.status.id)} text-white font-semibold flex items-center justify-center`"
                              class="relative badge-position">{{ team.status.status }}</span>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          <Link :href="`/teams/${team.slug}`"
                                class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">
                            {{ team.name }}
                          </Link>
                        </th>
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ team.teamCreator }}
                        </th>
                        <td
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ team.memberSpots }}
                        </td>
                        <td
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                          {{ team.totalShows }}
                        </td>
                        <td class="px-6 py-4 space-x-2">
                          <button
                              v-if="team.can.viewTeam"
                              @click="userStore.btnRedirect(`/teams/${team.slug}/manage`)"
                              class="px-4 py-2 text-white bg-purple-600 hover:bg-purple-500 rounded-lg"
                          >Manage
                          </button>
                          <button
                              v-if="team.can.editTeam"
                              @click="userStore.btnRedirect(`/teams/${team.slug}/edit`)"
                              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                          >Edit
                          </button>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                    <!-- Paginator -->
                    <Pagination :data="teams" class="pb-6"/>
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
import { Inertia } from "@inertiajs/inertia"
import { onBeforeMount, onMounted, ref, watch } from "vue"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"
import AdminHeader from "@/Components/Pages/Admin/AdminHeader"
import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

let props = defineProps({
  teams: Object,
  filters: Object,
  can: Object,
});

let search = ref(props.filters.search)

userStore.currentPage = 'adminTeams'
userStore.showFlashMessage = true;

onMounted(() => {
  videoPlayerStore.makeVideoTopRight()
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()
});

watch(search, throttle(function (value) {
  Inertia.get('/admin/teams', {search: value}, {
    preserveState: true,
    replace: true
  });
}, 300));

function getBadgeColor(statusId) {
  switch (statusId) {
    case 1:
      return 'bg-green-500';     // Green
    case 2:
      return 'bg-blue-500';      // Blue
    case 3:
      return 'bg-purple-500';    // Purple
    case 4:
      return 'bg-orange-500';    // Orange
    case 5:
      return 'bg-red-500';       // Red
    case 6:
      return 'darkgray-italic';  // Custom class
    case 7:
      return 'black-italic';     // Custom class
    case 8:
      return 'black-italic';     // Custom class
    case 9:
      return 'red-italic';       // Custom class
    case 10:
      return 'red-italic';      // Custom class
    case 11:
      return 'bg-gray-700';     // Dark gray
    default:
      return 'bg-gray-300';     // Default color
  }
}

</script>

<style scoped>
.badge-position {
  position: relative;
  left: 4rem; /* Move to the right */
  top: -5rem; /* Move upwards */
}

.darkgray-italic {
  color: darkgray;
  font-style: italic;
}

.black-italic {
  color: black;
  font-style: italic;
}

.red-italic {
  color: red;
  font-style: italic;
}
</style>

