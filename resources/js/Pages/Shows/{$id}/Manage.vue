<template>

  <Head :title="`Manage Show: ${props.show.name}`"/>

  <div id="topDiv" class="place-self-center flex flex-col">
    <div class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-50 rounded px-5">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <div class="alert alert-error mt-4 mx-3"
           v-if="showStore.errorMessage">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ showStore.errorMessage }}</span>
        <button class="text-xs ml-12" @click="showStore.errorMessage = ''"> Close</button>
      </div>

      <header class="bg-gradient-to-r from-blue-100 via-white to-transparent p-4 text-black font-bold rounded-lg">
        <!--            <header class="wave-background p-4 text-black font-bold rounded-lg">-->

        <div class="flex justify-between mb-3 pt-6">
          <div class="font-bold mb-4 text-black align-bottom text-lg">MANAGE SHOW</div>

          <div class="flex flex-col">
            <div class="flex flex-row w-full justify-end mb-2">
              <button
                  @click="appSettingStore.btnRedirect(`/teams/${team.slug}/manage`)"
                  class="px-4 py-2 mr-2 mb-2 h-fit text-white font-semibold bg-orange-500 hover:bg-orange-600 rounded-lg"
              >Back to<br/>
                Team Page
              </button>
            </div>
            <div class="flex flex-wrap-reverse justify-end">
              <button
                  v-if="can.goLive"
                  @click="goLive"
                  class="px-4 py-2 mr-2 mb-2 h-fit text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg"
              >Go Live
              </button>
              <button
                  v-if="teamStore.can.editShow"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/edit`)"
                  class="px-4 py-2 mb-2 h-fit text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
              >Edit Show
              </button>

              <DashboardButton class="mb-2"/>

            </div>
          </div>

        </div>

        <ShowHeader
            :show="props.show"
            :team="props.team"
            :can="can"
        />

        <div class="flex justify-end mt-6">


        </div>
      </header>


      <!--            <div class="my-6 ml-10 w-3/4">-->
      <!--                {{ teamStore.activeShow.description }}-->
      <!--            </div>-->

      <div class="my-6 text-gray-100">
        <span class="uppercase text-xs font-semibold text-black">SHOW NOTES: </span>
        <span class="text-black light:text-black dark:text-white">{{ props.show.notes }}</span>
      </div>


      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block w-full sm:px-6 lg:px-8 space-y-4">
            <!--          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">-->
            <!--            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">-->

            <!-- This code doesn't work .. it's meant to become a header button that collapses/expands each section -->
            <!--                            <button class="bg-orange-300 p-2 font-bold w-full text-left" type="button"-->
            <!--                                    data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true"-->
            <!--                                    aria-controls="collapseExample">Episodes-->
            <!--                            </button>-->

            <!--                            <div class="collapse" id="collapseExample">-->
            <!--              <div>-->
            <!--                <div class="bg-blue-100 p-2 font-bold text-black">Episodes</div>-->
            <div @click="toggleComponent('showEpisodes')"
                 :class="{'rounded-t-lg': showStore.openComponent === 'showEpisodes', 'rounded-lg': showStore.openComponent !== 'showEpisodes'}"
                 class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-blue-100 hover:bg-blue-300 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
              <font-awesome-icon icon="fa-play-circle" class=""/>
              Episodes
            </div>
            <div v-if="showStore.openComponent === 'showEpisodes'">
              <div class="mt-4 mb-12 pb-6 shadow overflow-auto border-b border-gray-200 sm:rounded-lg">
                <ShowEpisodesList :episodes="props.episodes" :show="props.show"
                                  :episodeStatuses="props.episodeStatuses"/>
              </div>
            </div>
            <!--              </div>-->

            <!--                            <table class="min-w-full divide-y divide-gray-200">-->
            <!--                                <tbody class="bg-white divide-y divide-gray-200">-->
            <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
            <!--                                    <td class="px-6 py-4 whitespace-nowrap">-->
            <!--                                        <div class="flex items-center">-->
            <!--                                            <div>-->
            <!--                                                <div class="text-sm font-medium text-gray-900">-->
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            <!--                                                </div>-->
            <!--                                            </div>-->
            <!--                                        </div>-->
            <!--                                    </td>-->

            <!--                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">-->
            <!--                                        <Link :href="`/admin/users/edit/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>-->
            <!--                                    </td>-->
            <!--                                </tr>-->
            <!--                                </tbody>-->
            <!--                            </table>-->

            <!--                            &lt;!&ndash; Paginator &ndash;&gt;-->
            <!--                            <Pagination :links="episode.links" class="mt-6"/>-->

            <!--            </div>-->

            <!--            <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">-->
            <!--              <div class="bg-blue-100 p-2 font-bold text-black">Credits</div>-->
            <!--              -->
            <!--              -->
            <div @click="toggleComponent('showCredits')"
                 :class="{'rounded-t-lg': showStore.openComponent === 'showCredits', 'rounded-lg': showStore.openComponent !== 'showCredits'}"
                 class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-auto shadow-lg bg-blue-100 hover:bg-blue-300 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
              <font-awesome-icon icon="fa-clipboard-list" class=""/>
              Credits and Assignments
            </div>
            <div v-if="showStore.openComponent === 'showCredits'">
              <div class="mt-4 mb-12 pb-6 shadow overflow-auto border-b border-gray-200 sm:rounded-lg">
                <div class="border-1 border-t mb-3 bg-blue-100 py-1 px-2 text-xs font-semibold text-red-800">
                  In development. Not currently working.
                </div>
                <Link
                    :href="`#`"
                    v-if="teamStore.can.createAssignment">
                  <button
                      class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                      disabled
                  >Create Assignment
                  </button>
                </Link>

                <ShowCreditsList/>
              </div>
            </div>
            <!--            </div>-->

            <!--            <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">-->
            <!--              <div class="bg-blue-100 p-2 font-bold text-black">Recordings</div>-->
            <div @click="toggleComponent('showRecordings')"
                 :class="{'rounded-t-lg': showStore.openComponent === 'showRecordings', 'rounded-lg': showStore.openComponent !== 'showRecordings'}"
                 class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-auto shadow-lg bg-blue-100 hover:bg-blue-300 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
              <font-awesome-icon icon="fa-circle" class="text-red-700"/>
              Recordings
            </div>
            <div v-if="showStore.openComponent === 'showRecordings'">
              <div class="mt-4 mb-12 pb-6 shadow overflow-auto border-b border-gray-200 sm:rounded-lg">
                <ShowRecordings :showRecordings="show.recordings" :showName="show.name" :showSlug="show.slug" :showImage="show.image"/>

                <!--            </div>-->
              </div>
            </div>
          </div>
        </div>
      </div>
      <ShowFooter :team="team" :show="show"/>
    </div>
  </div>

</template>

<script setup>
import { onUnmounted } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import ShowHeader from '@/Components/Pages/Shows/Layout/ShowHeader'
import ShowFooter from '@/Components/Pages/Shows/Layout/ShowFooter'
import ShowEpisodesList from '@/Components/Pages/Shows/Elements/ManageShowEpisodesList'
import ShowCreditsList from '@/Components/Pages/Shows/Elements/ManageShowCreditsList'
import Message from '@/Components/Global/Modals/Messages'
import DashboardButton from '@/Components/Global/Buttons/DashboardButton.vue'
import ShowRecordings from '@/Components/Pages/Shows/Elements/ShowRecordings.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { Inertia } from '@inertiajs/inertia'

usePageSetup('shows/slug/manage')

const appSettingStore = useAppSettingStore()
const showStore = useShowStore()
const teamStore = useTeamStore()
const goLiveStore = useGoLiveStore()

const toggleComponent = (componentName) => {
  showStore.openComponent = showStore.openComponent === componentName ? null : componentName
}


onUnmounted(() => {
  showStore.errorMessage = ''
  // const topDiv = document.getElementById("topDiv");
  // if (topDiv) {
  //   topDiv.scrollIntoView();
  // }
})

let props = defineProps({
  show: Object,
  team: Object,
  episodes: Object,
  episodeStatuses: Object,
  // filters: Object,
  can: Object,
})

teamStore.setActiveTeam(props.team)
teamStore.setActiveShow(props.show)
teamStore.can = props.can

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

const goLive = () => {
  goLiveStore.reset()
  goLiveStore.preSelectedShowId = props.show.id
  appSettingStore.btnRedirect(`/golive`)
}

</script>


<style scoped>
@keyframes wave-bg {
  0%, 100% {
    background-color: #dbeafe; /* Original color */
  }
  50% {
    background-color: #bfdbfe; /* Slightly lighter color */
  }
}

.wave-background {
  animation: wave-bg 5s infinite ease-in-out;
  background-color: #dbeafe; /* Starting color */
}


</style>
