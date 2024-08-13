<template>
  <Head :title="pageTitle">
    <meta property="og:url" :content="ogUrl" />
    <meta property="og:type" :content="ogType" />
    <meta property="og:title" :content="ogTitle" />
    <meta property="og:description" :content="ogDescription" />
    <meta property="og:image" :content="ogImage" />
  </Head>

  <div id="topDiv" class="place-self-center min-h-screen flex flex-col">
    <div
        class="min-h-screen w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 rounded sm:rounded-lg shadow">

      <div class="flex flex-col justify-center max-w-7xl mx-auto">
        <div class="w-full">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <TeamIdIndexHeader />
      <TeamIdIndexBanner v-show="show"/>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Description and Creators section -->
        <div class="bg-gray-800 p-4 rounded-lg shadow-lg flex flex-col">
          <TeamIdIndexDescription />
          <TeamIdIndexCreators v-if="contributors.data.length > 0"/>
        </div>

        <!-- Search and list section with minimum height -->
        <div class="px-5 bg-gray-800 p-4 rounded-lg shadow-lg min-h-64">
          <TeamIdIndexSearchShowEpisodes />
          <TeamShowsList v-if="shows.data.length > 0"/>
        </div>
      </div>

      <div class="spacer min-h-32"></div>
    </div>

      </div>
    </div>

  </div>

</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import TeamShowsList from '@/Components/Pages/Teams/Elements/TeamShowsList'
import Message from '@/Components/Global/Modals/Messages'
import TeamIdIndexBanner from '@/Components/Pages/Teams/Elements/TeamIdIndexBanner.vue'
import TeamIdIndexCreators from '@/Components/Pages/Teams/Elements/TeamIdIndexCreators.vue'
import TeamIdIndexHeader from '@/Components/Pages/Teams/Elements/TeamIdIndexHeader.vue'
import TeamIdIndexDescription from '@/Components/Pages/Teams/Elements/TeamIdIndexDescription.vue'
import TeamIdIndexSearchShowEpisodes from '@/Components/Pages/Teams/Elements/TeamIdIndexSearchShowEpisodes.vue'
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
//
usePageSetup('teams/slug')
//
const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const page  = usePage().props
//
const props = defineProps({
  user: Object,
  team: Object,
  shows: Object,
  contributors: Object,
  filters: Object,
  can: Object,
})

// teamStore.setActiveTeam(props.team)

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

const show = ref(false);

onMounted(() => {
  teamStore.initializeTeam({ ...props.team })
  teamStore.initializeShows({ ...props.shows })
  teamStore.initializeContributors({ ...props.contributors })
  teamStore.setCan({ ...props.can })

  setTimeout(() => {
    show.value = true;
  }, 200); // 200 milliseconds
})

onBeforeUnmount(() => {
  teamStore.reset()
})

//
const pageTitle = computed(() => props.team.name)
const ogUrl = computed(() => `${page.appUrl}${page.currentPath}`);
const ogType = computed(() => 'website');
const ogTitle = computed(() => props.team.name);
// Truncate the description if it exceeds 300 characters
const ogDescription = computed(() => {
  const description = props.team.description;
  const maxLength = 300;
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description;
});
const ogImage = computed(() => {
  const image = props.team.image;
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
</script>