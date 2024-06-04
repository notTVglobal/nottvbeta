<template>
  <Head :title="`News Reporters`"/>
  <div id="topDiv" ></div>
  <div class="mt-16">
    <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu />
    <div class="h-screen bg-gray-900 flex flex-col gap-y-3 place-self-center text-white px-5">
      <PublicNewsNavigationButtons :can="null"/>

      <div class="text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">News Reporters</div>

      <main class="pb-8 mx-auto px-4 border-b border-gray-800">
        <div class="flex flex-col w-full bg-gray-200 my-10 mx-auto p-5 rounded justify-center text-gray-900">
          <div v-if="newsPeople.length === 0" class="w-full">
            <div class="flex flex-col gap-y-2">
              <p>We are in the process of building our news team.</p>
              <p>If you are an independent journalist please <Link @click.prevent="appSettingStore.btnRedirect('/contact')" class="underline text-blue-800 hover:text-blue-600 transition duration-300">contact us</Link>.</p>
            </div>
          </div>
          <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 w-full px-6">

            <div v-for="person in newsPeople" :key="person.id" class="text-center px-8 py-3">
              <div class="hover:bg-gray-300 rounded-lg cursor-pointer pb-8">
                <header class="pt-8 pb-6">
                  <Link :href="`/news/reporter/${person.slug}`">
                    <h3>{{ person.name }}</h3>
                  </Link>
                </header>
                <main class="flex justify-center">
                  <Link :href="`/news/reporter/${person.slug}`">
                    <img :src="person.profile_photo_url" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover">
                  </Link>
                </main>
                <footer class="mt-8">
                  Add other details here
                </footer>
              </div>
            </div>
          </section>
        </div>
      </main>

      <Footer/>
    </div>
  </div>
</template>




<script setup>
import { onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons'
import Footer from '@/Components/Global/Layout/Footer'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.currentPage = 'news.reporters'
appSettingStore.setPrevUrl()

const { props } = usePage();

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer();
    }, 1000); // Delay the disposal by 1000 milliseconds (1 second)
  } else (console.log('no video'))
  // appSettingStore.noVideo = true
})


// Watch for changes in the loggedIn state of appSettingStore
// watch(() => userStore.loggedIn, (loggedIn) => {
//   appSettingStore.noLayout = !loggedIn;
//
//   // Call usePageSetup if loggedIn is true
//   if (loggedIn) {
//     console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded);
//     usePageSetup('news.reporters');
//   }
//   nextTick(() => {
//     videoPlayerStore.makeVideoTopRight()
//     appSettingStore.pageIsHidden = false
//   });
// }, {
//   // immediate: true // This ensures the watcher runs immediately on setup
// });

defineProps({
  newsPeople: Array,
  can: Object,
})

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>
