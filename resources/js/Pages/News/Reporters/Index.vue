<template>
  <Head :title="`News Reporters`"/>
  <div id="topDiv" ></div>
  <div :class="marginTopClass">
    <PublicNavigationMenu v-if="!userStore.loggedIn" class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu v-if="!userStore.loggedIn" />
    <div class="h-screen bg-gray-900 flex flex-col gap-y-3 place-self-center text-white px-5">
      <PublicNewsNavigationButtons :can="can"/>
<!--      <Breadcrumbs :breadcrumbs="[{ text: 'Reporters', to: '' }]" />-->

      <div class="text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">News Reporters</div>

      <main class="pb-8 mx-auto px-4 border-b border-gray-800">
        <div class="flex flex-col w-full bg-gray-200 my-10 mx-auto p-5 rounded justify-center text-gray-900">
          <div v-if="newsPeople.length === 0" class="w-full">
            <div class="flex flex-col gap-y-2">
              <p>We are in the process of building our news team.</p>
              <p>If you are an independent journalist please <button @click.prevent="appSettingStore.btnRedirect('/contact')" class="underline text-blue-800 hover:text-blue-600 transition duration-300">contact us</button>.</p>
            </div>
          </div>
          <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 w-full px-6">

            <div v-for="person in newsPeople" :key="person.id" class="text-center px-8 py-3">
              <div class="hover:bg-gray-300 rounded-lg cursor-pointer pb-8">
                <header class="pt-8 pb-6">
                  <Link v-if="person && person.slug" :href="`/news/reporter/${person.slug}`">
                    <h3>{{ person.name }}</h3>
                  </Link>
                </header>
                <main class="flex justify-center">
                  <Link v-if="person && person.id" :href="`/news/reporter/${person.slug}`">
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

      <Footer v-if="!userStore.loggedIn"/>
    </div>
  </div>
</template>




<script setup>
import { computed, nextTick, onMounted, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons'
import Footer from '@/Components/Global/Layout/Footer'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

// appSettingStore.noLayout = true
appSettingStore.currentPage = 'news.reporters'
appSettingStore.setPrevUrl()

const { props } = usePage();

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
  // appSettingStore.noVideo = true
})


// Watch for changes in the loggedIn state of appSettingStore
watch(() => userStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn;

  // Call usePageSetup if loggedIn is true
  if (loggedIn) {
    console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded);
    usePageSetup('news.reporters');
  }
  nextTick(() => {
    videoPlayerStore.makeVideoTopRight()
    appSettingStore.pageIsHidden = false
  });
}, {
  // immediate: true // This ensures the watcher runs immediately on setup
});

defineProps({
  newsPeople: Array,
  can: Object,
})

const marginTopClass = computed(() => {
  return userStore.loggedIn ? '' : 'mt-16';
});

</script>

<!--<script>-->
<!--import NoLayout from '@/Layouts/NoLayout'-->

<!--export default {-->
<!--  layout: NoLayout,-->
<!--}-->
<!--</script>-->
