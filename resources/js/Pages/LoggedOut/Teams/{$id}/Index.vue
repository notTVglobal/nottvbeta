<template>

  <Head :title="props.team.name"/>

  <!--    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">-->
  <div id="topDiv" class="place-self-center h-screen flex flex-col">

    <PublicNavigationMenu/>
    <PublicResponsiveNavigationMenu/>
    <!--        <div id="topDiv" class="light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 rounded p-5 mb-10">-->
    <div
        class="min-h-screen w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 rounded sm:rounded-lg shadow pt-6 mt-16 overflow-y-scroll">

      <header class="flex flex-col justify-between pt-6 px-5">
        <div class="flex flex-row flex-wrap justify-between">
          <div class="flex flex-row">
            <SingleImage :image="image" :alt="'team logo'" :class="'w-40 mr-4'"/>
            <h3 class="light:text-gray-900 dark:text-gray-50 inline-flex items-center text-3xl font-semibold relative uppercase">
              {{ props.team.name }}
            </h3>
          </div>
          <div>
            <button @click="Inertia.visit('/teams')" class="btn btn-wide">Browse All Teams</button>
          </div>

        </div>
        <SocialMediaBadgeLinks :socialMediaLinks="team.socialMediaLinks"/>
      </header>

      <div v-if="team?.nextBroadcast || team.public_message" class="flex flex-row justify-center w-full py-10 px-5">
        <div class="flex flex-row text-red-950 bg-yellow-300 w-full py-6 text-center align-middle">
          <div class="flex flex-col w-1/3 border-r border-black">
            <div v-if="team?.nextBroadcast">
              <p class="uppercase font-bold tracking-wider">
                Next Broadcast
              </p>
              <p class="text-lg">
                April 20, 2024
              </p>
              <p class="text-lg">
                7:30pm PT
              </p>
              <p class="text-lg">
                Duncan Townhall
              </p>
            </div>
            <div v-else>
              No broadcasts are currently scheduled.
            </div>

          </div>
          <div class="flex flex-col w-2/3 justify-center font-semibold">
            {{ team.public_message }}
          </div>

        </div>
      </div>


      <!--      <p v-if="props.team.description" class="description mb-6 p-5">-->
      <!--        {{ props.team.description }}-->
      <!--      </p>-->


      <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="order-last lg:order-none">
          <p v-if="props.team.description" class="description mb-6 p-5">
            {{ truncatedDescription }}
            <span v-if="props.team.description.length > 300 && !showFullDescription">
              ...
            </span>
            <br/><br/>
            <button v-if="props.team.description.length > 300 && !showFullDescription"
                    @click="showFullDescription = true"
                    class="btn btn-wide justify-self-center">Read the full description
            </button>
            <span v-if="showFullDescription">
              {{ props.team.description }}
            </span>
          </p>
        </div>
        <div class="px-5">

          <div v-if="props.shows.data.length !== 0"
               class="w-full bg-gray-900 text-white text-center  text-2xl p-4 mb-4">
            SEARCH
            EPISODES
          </div>

          <SearchShowEpisodesComponent :modelType="`team`" :modelId="props.team.id" :modelSlug="props.team.slug">
            <!-- Provide custom title -->
            <template v-slot:title>
              <h2 class="text-white text-lg font-semibold mb-2">Advanced Episode Search</h2>
            </template>
            <!-- Provide custom description -->
            <template v-slot:description>
              <p class="text-gray-400 text-sm mb-4">Search through all of our shows to find episodes.</p>
            </template>
          </SearchShowEpisodesComponent>

          <div v-if="props.shows.data.length !== 0"
               class="w-full bg-gray-900 text-white text-center text-2xl p-4 mb-4">SHOWS
          </div>

          <TeamShowsList :shows="props.shows"/>
        </div>


      </div>


      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">


            <div hidden class="creators">
              <div class="w-full bg-gray-900 text-white text-2xl p-4 mb-8">CREATORS</div>
              <!-- Note: Team Members ("Creators") will now be hidden by default.
              Teams and creators need to opt-in to have public creator profiles
              and to be visible on Team, Shows, and Episode pages. This change
              is to enhance privacy and give more control to the individuals involved. -->

              <div class="flex flex-row flex-wrap justify-center">
                <div v-for="creator in props.creators.data" :key="creator.id" class="pb-8 bg-gray-800">
                  <div class="flex flex-col items-center min-w-[8rem] px-6 py-4 font-medium break-words">
                    <img v-if="!creator.profile_photo_path && creator.profile_photo_url"
                         :src="creator.profile_photo_url"
                         class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
                    <img v-if="creator.profile_photo_path" :src="'/storage/' + creator.profile_photo_path"
                         :alt="creator.name + ' profile photo'"
                         class="rounded-full h-32 w-32 min-h-32 min-w-32 object-cover mb-2">
                    <img v-else :src="'/storage/images/Ping.png'"
                         :alt="'no profile photo, using our ping logo as a placeholder'"
                         class="rounded-full h-32 w-32 min-h-32 min-w-32 object-cover mb-2">
                    <span class="text-gray-50 text-center">{{ creator.name }}</span>
                  </div>
                </div>
              </div>

            </div>


            <!--                            For now, we are just displaying the team members here.
                                            This will make a good component that can be re-used across
                                            the Show and Episode Index pages. Just pass in the creators prop.

                                            We will add this when we have our Creators model setup
                                            and creators attached to the credits table for this
                                            show.                                                       -->

            <!--                            <ShowCreatorsList />-->

            <!--  <TeamFooter />  -->

          </div>
        </div>
      </div>

      <Footer/>

    </div>
  </div>

</template>


<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useTeamStore } from '@/Stores/TeamStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import TeamShowsList from '@/Components/Pages/Teams/Elements/TeamShowsList'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import SearchShowEpisodesComponent from '@/Components/Global/Search/SearchShowEpisodesComponent.vue'
import { computed, onMounted, ref } from 'vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import { Inertia } from '@inertiajs/inertia'
import Footer from '@/Components/Global/Layout/Footer.vue'

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

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

const showFullDescription = ref(false)

const truncatedDescription = computed(() => {
  if (props.team.description.length <= 300 || showFullDescription) {
    return props.team.description
  } else {
    return props.team.description.slice(0, 300)
  }
})



// Function to handle scrolling
const scrollToTop = () => {
  const hasQueryStrings = window.location.search !== '';
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

<style scoped>
.description {
  overflow: hidden;
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  text-overflow: ellipsis;
  @apply tracking-wide
}
</style>
