<template>
  <Head :title="`News Reporters`"/>
  <div :class="marginTopClass">
    <PublicNavigationMenu v-if="!appSettingStore.loggedIn" class="fixed top-0 w-full nav-mask"/>

    <div class="bg-gray-900 flex flex-col gap-y-3 place-self-center text-white px-5">
      <PublicNewsNavigationButtons :can="can"/>
      <Breadcrumbs :breadcrumbs="[{ text: 'News', to: '/news' }, { text: 'Reporters', to: '' }]" />

      <div class="text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">News Reporters</div>

      <main class="pb-8 mx-auto px-4 border-b border-gray-800">
        <div class="flex bg-gray-200 my-10 mx-auto p-5 w-[calc(100%-96)] rounded justify-center text-gray-900">
          <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 w-full px-6">
            <div v-for="person in newsPeople" :key="person.id" class="text-center px-8 py-3">
              <div class="hover:bg-gray-300 rounded-lg cursor-pointer pb-8">
                <header class="pt-8 pb-6">
                  <Link :href="`/news/reporter/${person.id}`">
                    <h3>{{ person.name }}</h3>
                  </Link>
                </header>
                <main class="flex justify-center">
                  <Link :href="`/news/reporter/${person.id}`">
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

      <Footer v-if="!appSettingStore.loggedIn"/>
    </div>
  </div>
</template>




<script setup>
import { computed, nextTick, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { Link } from '@inertiajs/inertia-vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons'
import Footer from '@/Components/Global/Layout/Footer'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'news.reporters'

// Watch for changes in the loggedIn state of appSettingStore
watch(() => appSettingStore.loggedIn, (loggedIn) => {
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
  immediate: true // This ensures the watcher runs immediately on setup
});

const props = defineProps({
  newsPeople: Array,
  can: Object,
})

const marginTopClass = computed(() => {
  return appSettingStore.loggedIn ? '' : 'mt-16';
});

</script>

<!--<script>-->
<!--import NoLayout from '@/Layouts/NoLayout'-->

<!--export default {-->
<!--  layout: NoLayout,-->
<!--}-->
<!--</script>-->
