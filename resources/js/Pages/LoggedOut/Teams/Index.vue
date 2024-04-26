<template>
  <Head title="Teams"/>

  <div id="topDiv" class="place-self-center h-screen flex flex-col">

    <PublicNavigationMenu/>
    <PublicResponsiveNavigationMenu/>
    <div class="h-screen mt-16 overflow-y-scroll">

            <div class="bg-gray-700 text-gray-50 dark:bg-gray-800 dark:text-gray-50 shadow rounded sm:rounded-lg">

                <div class="p-6">

                  <div class="flex flex-row w-full justify-between items-center">
                    <!-- Paginator -->
<!--                    <Pagination :data="teams" class=""/>-->

                    <h1 class="text-3xl font-semibold pb-3 text-white">Teams</h1>
                    <div class="gap-x-4 mb-4">
                      <input v-model="search" type="search" placeholder="Search Teams..."
                             class="border px-2 rounded-lg"/>
                    </div>
                  </div>

                  <div class="gap-2 flex flex-row flex-wrap w-full overflow-x-none">
                    <div v-for="team in teams.data"
                         :key="team.id"
                         @click="Inertia.visit(`/teams/${team.slug}`)"
                         class=" bg-gray-300 shadow-md hover:bg-yellow-400 hover:cursor-pointer flex flex-col max-w-[12rem] justify-center items-center py-4 rounded-lg">

                      <div class="flex items-center justify-center min-w-[12rem] px-6 pb-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        <!--                                                <img :src="`/storage/${team.logo}`" class="rounded-full h-20 w-20 object-cover">-->
                        <!--                                                <img :src="'/storage/images/' + team.logo" class="rounded-full h-20 w-20 object-cover">-->
                        <SingleImage :image="team.image"
                                     :alt="'team logo rounded full'"
                                     :key="props.image"
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


            </div>

    </div>
  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { onMounted, ref, watch } from 'vue'

import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import Pagination from '@/Components/Global/Paginators/Pagination'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.currentPage = 'teams'
appSettingStore.setPrevUrl()

let props = defineProps({
  teams: Object,
  filters: Object,
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/teams', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById("topDiv");
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({ behavior: 'smooth' });
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  });
};
scrollToTop(); // Optionally scroll to top when the component mounts

onMounted(() => {
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }

})

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

