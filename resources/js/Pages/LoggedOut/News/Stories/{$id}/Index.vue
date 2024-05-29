<template>
  <Head :title="pageTitle">
    <!-- Open Graph Meta Tags -->
    <meta property="og:url" :content="ogUrl" />
    <meta property="og:type" :content="ogType" />
    <meta property="og:title" :content="ogTitle" />
    <meta property="og:description" :content="ogDescription" />
    <meta property="og:image" :content="ogImage" />
    <meta property="og:image:alt" :content="twitterImageAlt" /> <!-- Optional: for better accessibility -->

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" :content="twitterCard" />
    <meta name="twitter:site" :content="twitterSite" />
    <meta name="twitter:creator" :content="twitterCreator" />
    <meta name="twitter:title" :content="ogTitle" />
    <meta name="twitter:description" :content="ogDescription" />
    <meta name="twitter:image" :content="ogImage" />
    <meta name="twitter:image:alt" :content="twitterImageAlt" />
  </Head>
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
import { usePage } from '@inertiajs/vue3'
import { computed, onMounted } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'

import { useNewsStore } from '@/Stores/NewsStore'

import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import NewsStoryHeader from '@/Components/Pages/News/Stories/NewsStoryHeader.vue'
import NewsStoryMain from '@/Components/Pages/News/Stories/NewsStoryMain.vue'
// import TipTapViewOnly from '@/Components/Global/TextEditor/TipTapViewOnly.vue'

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()
const videoPlayerStore = useVideoPlayerStore()
const page  = usePage().props

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

const pageTitle = computed(() => props.newsStory.title)
const ogUrl = computed(() => `${page.appUrl}${page.currentPath}`);
const ogType = computed(() => 'article');
const ogTitle = computed(() => props.newsStory.title);
// Truncate the description if it exceeds 300 characters
const ogDescription = computed(() => {
  const description = props.newsStory.content;
  const maxLength = 300;
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description;
});
const ogImage = computed(() => {
  const image = props.newsStory.image;
  if (image) {
    const { cdn_endpoint, cloud_folder, name, placeholder_url } = image;
    if (cdn_endpoint && cloud_folder && name) {
      return `${cdn_endpoint}${cloud_folder}${name}`;
    } else if (placeholder_url) {
      return placeholder_url;
    }
  }
  return null;
});
const twitterCard = computed(() => 'summary_large_image'); // Type of Twitter card
const twitterSite = computed(() => '@notTV'); // Your Twitter handle
const twitterCreator = computed(() => {
  return props?.newsStory?.newsPerson?.socialMediaLinks?.twitter_handle || '';
});
const twitterImageAlt = computed(() => props.newsStory.title + ' Image'); // Alt text for the image


</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

