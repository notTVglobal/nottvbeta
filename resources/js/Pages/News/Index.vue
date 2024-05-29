<template>
  <Head title="News"/>
  <div id="topDiv" ></div>
  <div class="flex flex-col min-h-screen bg-gray-50 text-black w-full overflow-x-hidden">

    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">
      <PublicNewsNavigationButtons/>

    </header>

<!--    <PublicNavigationMenu v-if="!userStore.loggedIn" class="fixed top-0 w-full nav-mask"/>-->
<!--    <PublicResponsiveNavigationMenu v-if="!userStore.loggedIn" />-->
    <main class="max-w-7xl flex flex-col justify-center flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <section>
          <NewsStoriesTable :newsStories="newsStories" :filters="filters" :can="can"/>
        </section>

      </div>
    </main>

<!--    <Footer v-if="!userStore.loggedIn"/>-->

  </div>
</template>

<script setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
// import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
// import Footer from '@/Components/Global/Layout/Footer.vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useForm, usePage } from '@inertiajs/vue3'
import throttle from 'lodash/throttle'
import { router } from '@inertiajs/vue3'
import NewsStoriesTable from '@/Components/Pages/Newsroom/NewsStoriesTable.vue'
// import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

const { props } = usePage();

usePageSetup('news');

appSettingStore.currentPage = 'news'
appSettingStore.setPrevUrl()
//
// console.log('user name???? ' + props.user?.name)
//
// if (userStore.loggedIn) {
//
// } else {
//   appSettingStore.noLayout = true
//
// }


// const reloadVideo = () => {
//   videoPlayerStore.loadFirstPlay()
// }

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
  appSettingStore.currentPage = 'news'

  if (userStore.isMobile || window.innerWidth < 1024) {
    appSettingStore.ott = 0;
  } else {
    // appSettingStore.ott = 1;
    appSettingStore.showOttButtons = true;
  }
})

// Watch for changes in the loggedIn state of appSettingStore
// watch(() => userStore.loggedIn, (loggedIn) => {
//   appSettingStore.noLayout = !loggedIn;
//
//   // Call usePageSetup if loggedIn is true
//   if (loggedIn) {
//     console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded);
//     usePageSetup('news');
//   }
//   nextTick(() => {
//     videoPlayerStore.makeVideoTopRight()
//     appSettingStore.pageIsHidden = false
//   });
// });

defineProps({
  newsStories: Object,
  filters: Object,
  can: Object,
})

let search = ref(props.filters.search)

// let form = useForm({})

watch(search, throttle(function (value) {
  router.get('/news', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

// const marginTopClass = computed(() => {
//   return userStore.loggedIn ? '' : 'mt-16';
// });

</script>
