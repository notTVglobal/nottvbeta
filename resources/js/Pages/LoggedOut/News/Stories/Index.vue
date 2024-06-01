<template>
  <Head title="News"/>
  <div id="topDiv" ></div>
  <div class="flex flex-col h-screen bg-gray-50 text-black w-full overflow-x-hidden overflow-y-auto mt-16">

    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">
      <PublicNewsNavigationButtons/>

    </header>

    <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu />
    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <div class="flex flex-col mx-auto justify-center max-w-7xl">
          <section>
            <NewsStoriesTable :newsStories="newsStories" :filters="filters" :can="null"/>
          </section>
        </div>
      </div>
    </main>

    <Footer />

  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import throttle from 'lodash/throttle'
import { router } from '@inertiajs/vue3'
import NewsStoriesTable from '@/Components/Pages/News/NewsStoriesTable.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

const { props } = usePage();

appSettingStore.currentPage = 'news'
appSettingStore.setPrevUrl()

onMounted(() => {
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer();
    }, 1000); // Delay the disposal by 1000 milliseconds (1 second)
  }
});

defineProps({
  newsStories: Object,
  filters: Object,
})

let search = ref(props.filters.search)

let form = useForm({})

watch(search, throttle(function (value) {
  router.get('/news', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

</script>
<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
  layout: NoLayout,
}
</script>
