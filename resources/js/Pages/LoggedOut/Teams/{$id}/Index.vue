<template>

  <Head :title="props.team.name"/>

  <div id="topDiv" class="place-self-center h-screen flex flex-col">

    <PublicNavigationMenu/>
    <PublicResponsiveNavigationMenu/>
    <div
        class="min-h-screen w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 rounded sm:rounded-lg shadow pt-6 mt-16 overflow-y-scroll">

      <TeamIdIndexHeader :team="team" :image="image"/>
      <TeamIdIndexBanner :team="team"/>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Description and Creators section -->
        <div class="bg-gray-800 p-4 rounded-lg shadow-lg flex flex-col">
          <TeamIdIndexDescription :team="team"/>
          <TeamIdIndexCreators :creators="creators"/>
        </div>

        <!-- Search and list section with minimum height -->
        <div class="px-5 bg-gray-800 p-4 rounded-lg shadow-lg min-h-64">
          <TeamIdIndexSearchShowEpisodes :shows="shows" :team="team"/>
          <TeamShowsList :shows="shows"/>
        </div>
      </div>

      <Footer/>

    </div>
  </div>

</template>


<script setup>
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import TeamShowsList from '@/Components/Pages/Teams/Elements/TeamShowsList'
import SearchShowEpisodesComponent from '@/Components/Global/Search/SearchShowEpisodesComponent.vue'
import { onMounted } from 'vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'

import Footer from '@/Components/Global/Layout/Footer.vue'
import TeamIdIndexHeader from '@/Components/Pages/Teams/Elements/TeamIdIndexHeader.vue'
import TeamIdIndexBanner from '@/Components/Pages/Teams/Elements/TeamIdIndexBanner.vue'
import TeamIdIndexCreators from '@/Components/Pages/Teams/Elements/TeamIdIndexCreators.vue'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import ShowCreatorsList from '@/Components/Pages/Shows/Elements/ShowCreatorsList.vue'
import TeamIdIndexDescription from '@/Components/Pages/Teams/Elements/TeamIdIndexDescription.vue'
import TeamIdIndexSearchShowEpisodes from '@/Components/Pages/Teams/Elements/TeamIdIndexSearchShowEpisodes.vue'

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

const props = defineProps({
  user: Object,
  team: Object,
  logo: String,
  image: Object,
  shows: Object,
  creators: Object,
  filters: Object,
})

appSettingStore.currentPage = `teams.${props.team.slug}`
appSettingStore.setPrevUrl()

teamStore.setActiveTeam(props.team)
teamStore.can = props.can


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

})

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>


