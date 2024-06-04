<template>
  <Head :title="$page.props.newsPerson.name" />
  <div id="topDiv" ></div>
  <div :class="marginTopClass">
    <PublicNavigationMenu v-if="!userStore.loggedIn" class="fixed top-0 w-full nav-mask" />
    <PublicResponsiveNavigationMenu v-if="!userStore.loggedIn" />
    <div class="bg-gray-900 flex flex-col gap-y-3 w-full place-self-center text-white px-5">

      <ShowNewsReporterHeader />
      <ShowNewsReporter />

      <Footer v-if="!userStore.loggedIn"/>
    </div>
  </div>
</template>


<script setup>
import { computed, nextTick, onMounted, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ShowNewsReporter from '@/Components/Pages/NewsReporters/ShowNewsReporter.vue'
import ShowCreatorsHeader from '@/Components/Pages/Creators/ShowCreatorsHeader.vue'
import ShowNewsReporterHeader from '@/Components/Pages/NewsReporters/ShowNewsReporterHeader.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

// appSettingStore.noLayout = true
appSettingStore.currentPage = 'news.reporter.id'
appSettingStore.setPrevUrl()

defineProps({
  can: Object,
})

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
})


// Watch for changes in the loggedIn state of appSettingStore
watch(() => userStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn;

  // Call usePageSetup if loggedIn is true
  if (loggedIn) {
    console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded);
    usePageSetup('news.reporter.id');
  }
  nextTick(() => {
    videoPlayerStore.makeVideoTopRight()
    appSettingStore.pageIsHidden = false
  });
}, {
  // immediate: true // This ensures the watcher runs immediately on setup
});

const marginTopClass = computed(() => {
  return userStore.loggedIn ? '' : 'mt-16'
})

</script>

