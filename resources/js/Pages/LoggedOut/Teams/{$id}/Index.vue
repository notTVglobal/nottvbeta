<template>
  <Head :title="pageTitle">
    <!-- Open Graph Meta Tags -->
    <meta property="og:url" :content="ogUrl"/>
    <meta property="og:type" :content="ogType"/>
    <meta property="og:title" :content="ogTitle"/>
    <meta property="og:description" :content="ogDescription"/>
    <meta property="og:image" :content="ogImage"/>
    <meta property="og:image:alt" :content="twitterImageAlt"/> <!-- Optional: for better accessibility -->

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" :content="twitterCard"/>
    <meta name="twitter:site" :content="twitterSite"/>
    <meta name="twitter:creator" :content="twitterCreator"/>
    <meta name="twitter:title" :content="ogTitle"/>
    <meta name="twitter:description" :content="ogDescription"/>
    <meta name="twitter:image" :content="ogImage"/>
    <meta name="twitter:image:alt" :content="twitterImageAlt"/>
  </Head>


  <div id="topDiv" class="place-self-center h-screen flex flex-col">

    <PublicNavigationMenu/>
    <PublicResponsiveNavigationMenu/>
    <div
        class="min-h-screen w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 rounded sm:rounded-lg shadow pt-6 mt-16 overflow-y-scroll">

      <div class="flex flex-col mx-auto justify-center max-w-7xl">

        <TeamIdIndexHeader/>
        <TeamIdIndexBanner/>

        <div class="flex w-full justify-center mt-2 mb-8">
          <div class="max-w-lg text-center">
            <LoginToWatch />
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <!-- Description and Creators section -->
          <div class="bg-gray-800 p-4 rounded-lg shadow-lg flex flex-col">
            <TeamIdIndexDescription/>
            <TeamIdIndexCreators/>
          </div>

          <!-- Search and list section with minimum height -->
          <div class="px-5 bg-gray-800 p-4 rounded-lg shadow-lg min-h-64">
            <TeamIdIndexSearchShowEpisodes/>
            <TeamShowsList/>
          </div>
        </div>
      </div>

      <Footer/>

    </div>
  </div>

</template>


<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed, onBeforeUnmount, onMounted } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import TeamShowsList from '@/Components/Pages/Teams/Elements/TeamShowsList'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import TeamIdIndexHeader from '@/Components/Pages/Teams/Elements/TeamIdIndexHeader.vue'
import TeamIdIndexBanner from '@/Components/Pages/Teams/Elements/TeamIdIndexBanner.vue'
import TeamIdIndexCreators from '@/Components/Pages/Teams/Elements/TeamIdIndexCreators.vue'
import TeamIdIndexDescription from '@/Components/Pages/Teams/Elements/TeamIdIndexDescription.vue'
import TeamIdIndexSearchShowEpisodes from '@/Components/Pages/Teams/Elements/TeamIdIndexSearchShowEpisodes.vue'
import LoginToWatch from '@/Components/Global/Banners/LoginToWatch.vue'

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const page = usePage().props

const props = defineProps({
  user: Object,
  team: Object,
  shows: Object,
  contributors: Object,
  filters: Object,
})

appSettingStore.currentPage = `teams.${props.team.slug}`
appSettingStore.setPrevUrl()

teamStore.setActiveTeam(props.team)


// Function to handle scrolling
const scrollToTop = () => {
  const hasQueryStrings = window.location.search !== ''
  if (!hasQueryStrings || appSettingStore.shouldScrollToTop) {
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
}
scrollToTop() // Optionally scroll to top when the component mounts


onMounted(() => {
  teamStore.initializeShows({...props.shows})
  teamStore.initializeTeam({...props.team})
  teamStore.initializeContributors({...props.contributors})
  teamStore.setCan({...props.can})

})

onBeforeUnmount(() => {
  teamStore.reset()
})


const pageTitle = computed(() => props.team.name)
const ogUrl = computed(() => `${page.appUrl}${page.currentPath}`)
const ogType = computed(() => 'website')
const ogTitle = computed(() => props.team.name)
// Truncate the description if it exceeds 300 characters
const ogDescription = computed(() => {
  const description = props.team.description
  const maxLength = 300
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description
})
const ogImage = computed(() => {
  const image = props.team.image
  if (image) {
    const {cdn_endpoint, cloud_folder, name, placeholder_url} = image
    if (cdn_endpoint && cloud_folder && name) {
      return `${cdn_endpoint}${cloud_folder}${name}`
    } else if (placeholder_url) {
      return placeholder_url
    }
  }
  return null
})
const twitterCard = computed(() => 'summary_large_image') // Type of Twitter card
const twitterSite = computed(() => '@notTV') // Your Twitter handle
const twitterCreator = computed(() => {
  return props?.team?.socialMediaLinks?.twitter_handle || ''
})
const twitterImageAlt = computed(() => props.team.name + ' Logo') // Alt text for the image


</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>


