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


  <div id="topDiv" class="place-self-center flex flex-col h-screen">
    <div class="min-h-screen w-full bg-gray-900 text-gray-50 pt-6 mt-16 overflow-y-scroll">

      <PublicNavigationMenu/>
      <PublicResponsiveNavigationMenu/>

      <div class="flex flex-col justify-center mx-auto max-w-5xl">
        <header class="p-5 mb-6">
          <div class="flex flex-col md:flex-row flex-wrap justify-between px-5">
            <div class="w-full md:w-3/4">
              <div class="mb-4">
                <h3 class="mb-1 inline-flex items-center text-3xl font-semibold relative">


                  {{ episode.name }}


                </h3>
                <div class="mb-1">
                  <span class="font-semibold text-xl hover:text-blue-400 text-blue-500 hover:cursor-pointer">
                      <Link :href="`/shows/${show.slug}/`">{{ show.name }}</Link>
                  </span>
                </div>
                <Link :href="`/teams/${team.slug}`" class="text-blue-300 hover:text-blue-500"><span
                    class="text-sm uppercase font-semibold">{{ team.name }}</span></Link>
                <div class="text-xs space-y-1">

                </div>
              </div>
              <div v-if="episode.release_dateTime" class="text-yellow-500">
                {{ formatDate(episode.release_dateTime) }}
              </div>
              <ConvertDateTimeToTimeAgo v-if="episode.scheduled_release_dateTime"
                                        :dateTime="episode.scheduled_release_dateTime" :class="`text-green-400`"/>


              <div class="text-gray-500 mt-1" v-if="!episode.episode_number">Episode {{ episode.id }}</div>
              <div class="text-gray-500 mt-1" v-if="episode.episode_number">Episode {{
                  episode.episode_number
                }}
              </div>
            </div>

            <div class="flex flex-col text-left md:text-right w-full md:w-1/4">
            <span class="text-lg uppercase justify-end tracking-wider text-yellow-700">{{
                show?.category?.name
              }}</span>
              <span class="tracking-wide text-yellow-500">{{ show?.subCategory?.name }}</span>
            </div>
            <div class="my-6">
              <ShareButton/>
            </div>

            <LoginToWatch />
          </div>



        </header>

        <div class="my-6 py-5 px-8">
          <div class="font-semibold text-xs uppercase mb-3">EPISODE DESCRIPTION</div>
          <!--          <div class="description">{{ episode.description }}</div>-->
          <ExpandableDescription :description="episode.description" :hideTitle="true"/>
        </div>


        <div
            class="flex flex-wrap justify-center shadow overflow-hidden border-y border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">
          <!--            <div class="flex flex-wrap items-start ml-5 py-0">-->
          <div class="max-w-[50%] ml-5 py-0">

            <SingleImageWithModal :image="episode.image" :key="episode.image"/>

          </div>

          <!--                                <img :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">-->


        </div>

        <div class="flex flex-col px-5">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

              <div class="mb-6 p-5">

                <div v-if="episode?.bonusContent">
                  <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
                  <div class="mb-8 p-4">
                <span
                    class="text-orange-500">Bonus content will go here. This includes content mentioned in an episode.</span>
                  </div>
                </div>


                <div hidden>
                  <div class="w-full bg-gray-800 text-2xl p-4 mb-8">CREDITS</div>


                  <div class="flex flex-row flex-wrap">
                    <div v-for="creator in props.creators.data"
                         :key="creator.id"
                         class="pb-8 light:bg-light dark:bg-gray-900">

                      <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                        <img :src="'/storage/' + creator.profile_photo_path"
                             class="pb-2 rounded-full h-32 w-32 object-cover mb-2">
                        <span class="light:text-gray-800 dark:text-gray-200 w-full text-center">{{
                            creator.name
                          }}</span>
                      </div>

                      <!--                            For now, we are just displaying the team members here.
                                                      This will make a good component that can be re-used across
                                                      the Show and Episode Index pages. Just pass in the creators prop.

                                                      We will add this when we have our Creators model setup
                                                      and creators attached to the credits table for this
                                                      show.                                                       -->

                      <!--                            <ShowCreatorsList />-->

                    </div>
                  </div>
                </div>


              </div>

              <EpisodeFooter :can="can" :team="team" :episode="episode" :show="show"/>
            </div>
          </div>
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
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import EpisodeFooter from '@/Components/Pages/ShowEpisodes/Layout/EpisodeFooter'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import ComingSoonShareAndSaveButtons from '@/Components/Global/UserActions/ComingSoonShareAndSaveButtons.vue'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import LoginToWatch from '@/Components/Global/Banners/LoginToWatch.vue'

const appSettingStore = useAppSettingStore()
const nowPlayingStore = useNowPlayingStore()
const videoPlayerStore = useVideoPlayerStore()
const teamStore = useTeamStore()
const userStore = useUserStore()
const page = usePage().props

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  image: Object,
  creators: Object,
  can: Object,
})

appSettingStore.currentPage = `shows.${props.show.slug}.episodes.${props.episode.slug}`
appSettingStore.setPrevUrl()

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})

teamStore.slug = props.team.slug
teamStore.name = props.team.name


//
// function checkForVideo() {
//     if (props.video.file_name && props.video.upload_status !== 'processing') {
//         videoPlayerStore.hasVideo = true;
//     } if (props.episode.youtube_url) {
//         videoPlayerStore.hasVideo = true;
//     } else
//     if (props.episode.video.video_url) {
//         videoPlayerStore.hasVideo = true;
//     } else
//     if (!props.episode.video.video_url && props.video.upload_status === 'processing'){
//         videoPlayerStore.hasVideo = false;
//     } else if (!props.video.file_name && !props.episode.video.video_url) {
//         videoPlayerStore.hasVideo = false;
//     } return true;
// }
//
// checkForVideo()

const pageTitle = computed(() => {
  const showName = props.show?.name ?? ''
  const episodeName = props.episode?.name ?? ''
  return showName && episodeName ? `${episodeName}: ${showName}` : episodeName || showName
})
const ogUrl = computed(() => `${page.appUrl}${page.currentPath}`)
const ogType = computed(() => 'video.episode')
const ogTitle = computed(() => props.episode.name)
// Truncate the description if it exceeds 300 characters
const ogDescription = computed(() => {
  const description = props.episode.description
  const maxLength = 300
  return description.length > maxLength ? `${description.substring(0, maxLength)}...` : description
})
const ogImage = computed(() => {
  const image = props.episode.image
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
  return props?.show?.socialMediaLinks?.twitter_handle || ''
})
const twitterImageAlt = computed(() => props.episode.name + ' Poster') // Alt text for the image


</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

<style scoped>
.description {
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  @apply tracking-wide
}
</style>
