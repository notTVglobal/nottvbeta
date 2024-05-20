<template>
  <Head :title="newsStory.title"/>
  <div id="topDiv">

    <div class="flex flex-col h-screen w-full bg-gray-50 overflow-x-hidden overflow-y-auto mt-16">
      <div class="place-self-center flex flex-col gap-y-3 bg-gray-50 w-full">

        <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
        <PublicResponsiveNavigationMenu/>

        <div class="bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">


        <NewsStoryHeader :newsStory="newsStory"/>
        <NewsStoryMain :newsStory="newsStory"/>


        </div>
      </div>

      <Footer/>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'

import BackButton from '@/Components/Global/Buttons/BackButton.vue'

import { useNewsStore } from '@/Stores/NewsStore'

import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import NewsStoryHeader from '@/Components/Pages/News/Stories/NewsStoryHeader.vue'
import NewsStoryMain from '@/Components/Pages/News/Stories/NewsStoryMain.vue'
// import TipTapViewOnly from '@/Components/Global/TextEditor/TipTapViewOnly.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const newsStore = useNewsStore()
const videoPlayerStore = useVideoPlayerStore()

let props = defineProps({
  newsStory: Object,
  can: Object,
})

appSettingStore.currentPage = `/news/story/${props.newsStory.slug}`
appSettingStore.setPrevUrl()

onMounted(() => {
  newsStore.reset()
  // newsStore.content_json = JSON.parse(props.newsStory.content_json)
  newsStore.content = props.newsStory.content
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }
})

// Watch for changes in the loggedIn state of appSettingStore
// watch(() => userStore.loggedIn, (loggedIn) => {
//   appSettingStore.noLayout = !loggedIn;
//
//   // Call usePageSetup if loggedIn is true
//   if (loggedIn) {
//     usePageSetup(`news.${props.newsStory.slug}`);
//   }
// });


</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

