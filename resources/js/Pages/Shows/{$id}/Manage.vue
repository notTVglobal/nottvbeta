<template>

  <Head :title="`Manage Show: ${props.show.name}`"/>

  <div id="topDiv" class="place-self-center flex flex-col">
    <div class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-50 rounded px-5 pb-36">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>
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
          <div class="font-bold mb-4 text-black">MANAGE SHOW</div>
          <div>
            <button
                @click="userStore.btnRedirect('/dashboard')"
                class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
            >Dashboard
            </button>

          </div>
        </div>

        <ShowHeader
            :show="props.show"
            :team="props.team"
        />

        <div class="flex justify-between mt-6">
          <div class="flex flex-col">
            <div><span class="text-xs  font-semibold uppercase">Team: </span>
              <Link :href="`/teams/${team.slug}/manage`" class="text-blue-500 ml-2 uppercase font-bold"> {{
                  team.name
                }}
              </Link>
            </div>
            <div><span class="text-xs  font-semibold mr-2 uppercase">Show Runner: </span><span
                class="font-bold"> {{ show.showRunner }} </span></div>
            <div><span class="text-xs  font-semibold mr-2 uppercase">Category: </span><span
                class="font-bold"> {{ show.categoryName }} </span></div>
            <div><span class="text-xs  font-semibold mr-2 uppercase">Sub-category: </span><span
                class="font-bold"> {{ show.subCategoryName }} </span></div>
          </div>
          <div>
            <button
                v-if="teamStore.can.editShow"
                @click="userStore.btnRedirect(`/shows/${show.slug}/edit`)"
                class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
            >Edit
            </button>
          </div>
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
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

              <!-- This code doesn't work .. it's meant to become a header button that collapses/expands each section -->
              <!--                            <button class="bg-orange-300 p-2 font-bold w-full text-left" type="button"-->
              <!--                                    data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true"-->
              <!--                                    aria-controls="collapseExample">Episodes-->
              <!--                            </button>-->

              <!--                            <div class="collapse" id="collapseExample">-->
              <div>
                <div class="bg-blue-100 p-2 font-bold text-black">Episodes</div>

                <ShowEpisodesList :episodes="props.episodes" :show="props.show"
                                  :episodeStatuses="props.episodeStatuses"/>
              </div>

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

            </div>

            <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
              <div class="bg-blue-100 p-2 font-bold text-black">Credits</div>
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
        </div>
      </div>
      <ShowFooter :team="props.team" :show="props.show"/>
    </div>
  </div>

</template>

<script setup>
import { onUnmounted } from "vue"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useShowStore } from "@/Stores/ShowStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useUserStore } from "@/Stores/UserStore"
import ShowHeader from "@/Components/Pages/Shows/Layout/ShowHeader"
import ShowEpisodesList from "@/Components/Pages/Shows/Manage/ShowEpisodesList"
import ShowFooter from "@/Components/Pages/Shows/Layout/ShowFooter"
import ShowCreditsList from "@/Components/Pages/Shows/Manage/ShowCreditsList"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('showsManage')

const showStore = useShowStore()
const teamStore = useTeamStore()
const userStore = useUserStore()

onUnmounted(() => {
  showStore.errorMessage = ''
})

let props = defineProps({
  show: Object,
  team: Object,
  episodes: Object,
  episodeStatuses: Object,
  // filters: Object,
  can: Object,
});

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
teamStore.can = props.can;

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));


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
