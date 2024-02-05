<template>
  <Head title="News"/>
  <div id="topDiv" ></div>
  <div class="flex flex-col min-h-screen bg-gray-50 text-black" :class="marginTopClass">

    <header class="place-self-center flex flex-col bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
      <PublicNewsNavigationButtons :can="can"/>
    </header>

    <PublicNavigationMenu v-if="!appSettingStore.loggedIn" class="fixed top-0 w-full nav-mask"/>

    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <section>
          <NewsStoriesTable :newsStories="newsStories" :filters="filters"/>
        </section>

      </div>
    </main>

    <Footer v-if="!appSettingStore.loggedIn"/>

  </div>
</template>

<script setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useForm } from '@inertiajs/inertia-vue3'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'
import NewsStoriesTable from '@/Components/Pages/Newsroom/NewsStoriesTable.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'news'

const reloadVideo = () => {
  videoPlayerStore.loadFirstPlay()
}

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
})

// Watch for changes in the loggedIn state of appSettingStore
watch(() => appSettingStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn;

  // Call usePageSetup if loggedIn is true
  if (loggedIn) {
    console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded);
    usePageSetup('news');
  }
  nextTick(() => {
    videoPlayerStore.makeVideoTopRight()
    appSettingStore.pageIsHidden = false
  });
}, {
  immediate: true // This ensures the watcher runs immediately on setup
});

const props = defineProps({
  newsStories: Object,
  filters: Object,
  can: Object,
})

let search = ref(props.filters.search)

let form = useForm({})

watch(search, throttle(function (value) {
  Inertia.get('/news', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

const marginTopClass = computed(() => {
  return appSettingStore.loggedIn ? '' : 'mt-16';
});

</script>
