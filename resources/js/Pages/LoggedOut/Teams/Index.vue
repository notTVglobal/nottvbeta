<template>
  <Head title="Browse Teams"/>

  <div class="place-self-center h-screen flex flex-col">
    <div id="topDiv"></div>

    <PublicNavigationMenu/>
    <PublicResponsiveNavigationMenu/>
    <div class="h-screen mt-16 overflow-y-scroll">

            <div class="min-h-screen bg-gray-700 text-gray-50 dark:bg-gray-800 dark:text-gray-50 shadow rounded sm:rounded-lg">

              <div class="flex flex-col mx-auto justify-center max-w-7xl">
                <TeamsIndexGrid :teams ="teams" :filters="filters"/>
              </div>

            </div>

      <Footer />

    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, ref, watch } from 'vue'

import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import Message from '@/Components/Global/Modals/Messages'


import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import TeamsIndexGrid from '@/Components/Pages/Teams/Elements/TeamsIndexGrid.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.currentPage = 'teams'
appSettingStore.setPrevUrl()

let props = defineProps({
  teams: Object,
  filters: Object,
})

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

