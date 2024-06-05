<template>
  <Head :title="$page.props.newsPerson.name" />
  <div id="topDiv" ></div>
  <div class="mt-16">
    <PublicNavigationMenu class="fixed top-0 w-full nav-mask" />
    <PublicResponsiveNavigationMenu />
    <div class="bg-gray-900 flex flex-col gap-y-3 w-full place-self-center text-white px-5">

      <ShowNewsReporterHeader />
      <ShowNewsReporter />

      <Footer />
    </div>
  </div>
</template>


<script setup>
import { onMounted } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ShowNewsReporter from '@/Components/Pages/NewsReporters/ShowNewsReporter.vue'
import ShowNewsReporterHeader from '@/Components/Pages/NewsReporters/ShowNewsReporterHeader.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.currentPage = 'news.reporter.id'
appSettingStore.setPrevUrl()

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer();
    }, 1000); // Delay the disposal by 1000 milliseconds (1 second)
  }
})
</script>

<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>