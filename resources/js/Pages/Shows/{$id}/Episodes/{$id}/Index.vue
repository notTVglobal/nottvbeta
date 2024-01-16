<template>

  <Head :title="`${props.show.name}: ${props.episode.name}`"/>


  <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">
    <div id="topDiv" class="text-white bg-gray-900 rounded py-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <div v-if="props.can.editShow || props.can.manageShow"
           class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 py-5">
        <div>
          <button
              v-if="props.can.manageShow"
              @click="userStore.btnRedirect(`/shows/${props.show.slug}/episode/${props.episode.slug}/manage`)"
              class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
          >Manage Episode
          </button>
        </div>
        <div>
          <button
              v-if="props.can.editShow"
              @click="userStore.btnRedirect(`/shows/${props.show.slug}/episode/${props.episode.slug}/edit`)"
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >Edit
          </button>
        </div>
        <div>
          <button
              v-if="props.can.manageShow"
              @click="userStore.btnRedirect(`/shows/${props.show.slug}/manage`)"
              class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
          >Manage Show
          </button>
        </div>
      </div>

      <header class="p-5 mb-6">
        <div class="flex flex-wrap justify-between px-5">
          <div class="">
            <div class="mb-4">
              <h3 class="mb-1 inline-flex items-center text-3xl font-semibold hover:text-blue-500 relative">
                <Link :href="`/shows/${props.show.slug}/`">

                  {{ props.show.name }}

                </Link>


              </h3>
              <div class="mb-1">
                                    <span class="font-semibold text-xl">
                                        {{ props.episode.name }}
                                        </span>
              </div>
              <div class="text-xs space-y-1">
                <span class="uppercase">Episode Number: </span>
                <span v-if="!episode.episode_number">{{ episode.id }}</span>
                <span v-if="props.episode.episode_number">{{ props.episode.episode_number }}</span>
              </div>
            </div>
            <div v-if="props.episode.release_dateTime">
              {{ formatDate(props.episode.release_dateTime) }}
            </div>

          </div>

          <div class="flex flex-col justify-end">
            <div class="">
              <span class="text-xs uppercase">Category: </span>
              <span class="text-sm uppercase font-semibold">{{ props.show.categoryName }}</span>
            </div>
            <div class="pb-4 hidden">
              <span class="text-xs uppercase">Sub-category: </span>
              <span class="text-sm uppercase font-semibold">{{ props.show.categorySubName }}</span>
            </div>
            <div v-if="props.can.viewCreator">
              <span class="text-xs uppercase">Team:</span>
              <Link :href="`/teams/${props.team.slug}`" class="text-blue-300 hover:text-blue-500 ml-2"><span
                  class="text-sm uppercase font-semibold">{{ props.team.name }}</span></Link>
            </div>
          </div>

        </div>

        <p v-if="episode.video.upload_status === 'processing' && !episode.video.video_url"
           class="mt-12 px-3 py-3 text-gray-50 mr-1 lg:mr-36 bg-black w-full text-center lg:text-left">
          The episode video is currently processing. Please check back later.
        </p>

        <div class="flex flex-wrap mt-12 m-auto lg:mx-0 justify-center lg:justify-start space-x-3 space-y-3">
          <div></div>
          <button v-if="episode.video.storage_location === 'spaces' || episode.video.video_url"
                  :disabled="episode.video.upload_status === 'processing' && !episode.video.video_url"
                  class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-700 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"
                  @click="playEpisode">
            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 485 485">
              <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
		s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
		C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
		S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
              <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
            </svg>
            <span v-if="videoPlayerStore.nowPlayingName === props.episode.name" class="ml-2">Now Playing</span>
            <span v-else class="ml-2">Watch Episode</span>
          </button>

          <button disabled
                  class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed">
            <span class=""><font-awesome-icon icon="fa-circle-down" class="mr-2"/>Save For Later</span>
          </button>

          <button disabled
                  class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed">
            <span class=""><font-awesome-icon icon="fa-share" class="mr-2"/>Share</span>
          </button>

        </div>

      </header>

      <div class="my-6 p-5">
        <div class="font-semibold text-xs uppercase mb-3">EPISODE DESCRIPTION</div>
        <div>{{ props.episode.description }}</div>
      </div>


      <div
          class="flex flex-wrap justify-center shadow overflow-hidden border-y border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">
        <!--            <div class="flex flex-wrap items-start ml-5 py-0">-->
        <div class="max-w-[50%] ml-5 py-0">
          <SingleImage :image="image" :key="image"/>

        </div>

        <!--                                <img :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">-->


      </div>

      <div class="flex flex-col px-5">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

            <div class="mb-6 p-5">

              <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
              <div class="mb-8 p-4">
                <span
                    class="text-orange-500">Bonus content will go here. This includes content mentioned in an episode.</span>
              </div>

              <div class="w-full bg-gray-800 text-2xl p-4 mb-8">CREDITS</div>


              <div class="flex flex-row flex-wrap">
                <div v-for="creator in props.creators.data"
                     :key="creator.id"
                     class="pb-8 light:bg-light dark:bg-gray-900">

                  <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                    <img :src="'/storage/' + creator.profile_photo_path"
                         class="pb-2 rounded-full h-32 w-32 object-cover mb-2">
                    <span class="light:text-gray-800 dark:text-gray-200 w-full text-center">{{ creator.name }}</span>
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

            <EpisodeFooter :can="props.can" :team="props.team" :epsiode="props.episode" :show="props.show"/>
          </div>
        </div>
      </div>

    </div>
  </div>

</template>


<script setup>
import { Inertia } from "@inertiajs/inertia"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useNowPlayingStore } from "@/Stores/NowPlayingStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useShowStore } from "@/Stores/ShowStore"
import { useUserStore } from "@/Stores/UserStore"
// import EpisodeHeader from "@/Components/Pages/ShowEpisodes/EpisodeHeader"
// import EpisodesList from "@/Components/Pages/ShowEpisodes/EpisodesList"
// import EpisodeCreditsList from "@/ComponentShows/Episodes/EpisodeCreditsList";
import EpisodeFooter from "@/Components/Pages/ShowEpisodes/Layout/EpisodeFooter"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

usePageSetup('showEpisodesShow')

const nowPlayingStore = useNowPlayingStore()
const teamStore = useTeamStore()
const showStore = useShowStore()
const userStore = useUserStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  image: Object,
  creators: Object,
  can: Object,
});

let playEpisode = () => {
  nowPlayingStore.reset()
  nowPlayingStore.show.name = props.show.name
  nowPlayingStore.show.url = `/shows/${props.show.slug}`
  nowPlayingStore.show.episode.url = `/shows/${props.show.slug}/episode/${props.episode.slug}`
  nowPlayingStore.show.episode.image = props.image
  nowPlayingStore.show.category = props.show.categoryName
  nowPlayingStore.show.categorySub = props.show.categorySubName
  nowPlayingStore.show.image = props.show.image
  videoPlayerStore.makeVideoFullPage()
  Inertia.visit('/stream')
  // if video has a file and is !processing, play file.
  if (props.episode.video.storage_location === 'spaces' && props.episode.video.upload_status !== 'processing') {
    videoPlayerStore.loadNewSourceFromFile(props.episode.video)
  } else if
      // else if url exists, play url
  (props.episode.video.video_url) {
    nowPlayingStore.isFromWeb = true
    nowPlayingStore.show.episode.name = props.episode.name
    videoPlayerStore.loadNewSourceFromUrl(props.episode.video)

  } else if
      // else if youtube_url exists, play youtube_url
  (props.episode.youtube_url) {
    nowPlayingStore.show.episode.name = props.episode.name
    videoPlayerStore.loadNewSourceFromYouTube(props.episode.youtube_url)
  }
}

teamStore.slug = props.team.slug;
teamStore.name = props.team.name;

function scrollTo(selector) {
  document.querySelector(selector).scrollIntoView({behavior: 'smooth'});
}

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

</script>
