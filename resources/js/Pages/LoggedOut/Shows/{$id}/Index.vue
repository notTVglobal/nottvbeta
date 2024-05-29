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

  <div id="topDiv" class="place-self-center flex flex-col h-screen">
    <div class="min-h-screen w-full bg-gray-900 text-gray-50 pt-6 mt-16 overflow-y-scroll">

      <PublicNavigationMenu/>
      <PublicResponsiveNavigationMenu/>

      <ShowIdIndexHeader :show="show" :team="team"/>

      <ShowIdIndexMain :show="show" :team="team" :episodes="episodes" :creators="creators" />

      <Footer/>

    </div>
  </div>

</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed, onMounted } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ShowIdIndexHeader from '@/Components/Pages/Shows/Elements/ShowIdIndexHeader.vue'
import ShowIdIndexMain from '@/Components/Pages/Shows/Elements/ShowIdIndexMain.vue'

const appSettingStore = useAppSettingStore()
const page  = usePage().props

let props = defineProps({
  show: Object,
  episodes: Object,
  creators: Object,
  team: Object,
})

appSettingStore.currentPage = `teams.${props.team.slug}`
appSettingStore.setPrevUrl()

// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({behavior: 'smooth'})
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({top: 0, behavior: 'smooth'})
    }
  })
}
scrollToTop() // Optionally scroll to top when the component mounts

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})

const pageTitle = computed(() => props.show.name)
const ogUrl = computed(() => `${page.appUrl}${page.currentPath}`);
const ogType = computed(() => 'video.tv_show');
const ogTitle = computed(() => props.show.name);
// Truncate the description if it exceeds 300 characters
const ogDescription = computed(() => {
  const description = props.show.description;
  const maxLength = 300;
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description;
});
const ogImage = computed(() => {
  const image = props.show.image;
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
  return props?.show?.socialMediaLinks?.twitter_handle || '';
});
const twitterImageAlt = computed(() => props.show.name + ' Poster'); // Alt text for the image

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>




