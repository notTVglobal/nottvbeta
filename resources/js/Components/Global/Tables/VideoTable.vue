<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div
        class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
    >
      <div
          class="table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
      >
        <div class="table-row">
          <div scope="col" class="table-cell px-6 py-3">
            <font-awesome-icon icon="fa-repeat" class="mr-2 cursor-pointer hover:text-blue-500"
                               @click.prevent="reload()"/>
            Filename
          </div>
          <div scope="col" class="hidden md:table-cell px-6 py-3">
            Size
          </div>
          <div scope="col" class="table-cell px-6 py-3">
            Date
          </div>
          <div scope="col" class="px-6 py-3">

          </div>
        </div>
      </div>
      <div class="table-row-group">
        <div
            v-for="video in videos.data"
            :key="video.id"
            class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700"
        >
          <div
              scope="row"
              class="table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
          >

            <Popper
                hover openDelay="50" closeDelay="50" offset-skid="0" offset-distance="0" placement="bottom"

            >
              <template #content>
                <div class="text-xs" id="tooltip">
                  <div v-if="video.file_name">Filename: {{ video.file_name ? video.file_name : 'No File Name' }}</div>
                  <div>ID: {{ video.id }}</div>
                  <div>Type: {{ video.type }}</div>
                  <div v-if="video.storage_location !== 'external'">Size: {{ video.size }}</div>
                  <div v-else>External</div>
                  <div v-if="video.user_id">Owner: {{ video.user_id }}</div>
                  <div v-if="video.showEpisode?.show">Show: {{ video.showEpisode.show.name }}</div>
                  <div v-if="video.showEpisode">Episode: {{ video.showEpisode.name }}</div>
                  <div v-if="video.movie">Movie: {{ video.movie.name }}</div>
                  <div v-if="video.movieTrailer">Trailer: {{ video.movieTrailer.name }}</div>
                  <div v-if="video.newsStory">News Post: {{ video.newsStory.name }}</div>
                </div>
              </template>


              <button
                  v-if="video.can.view"
                  @click.prevent="playVideo(video)"
                  :disabled="video.upload_status === 'processing'"
                  class="disabled:cursor-not-allowed disabled:text-gray-500 disabled:italic inline"
              >
                <div v-if="video.file_name">{{
                    video.file_name.length > 15 ? video.file_name.substring(0, 15 - 3) + "..." : video.file_name.substring(0, 15)
                  }}
                </div>
                <div v-else>{{ video.storage_location }}</div>
              </button>
            </Popper>
            <div class="flex flex-row space-x-1">
              <div v-if="video.showEpisode?.show"
                   class="w-fit text-xs rounded-lg px-1 uppercase bg-blue-800 text-white font-semibold hover:cursor-pointer">
                Episode
              </div>
              <div v-if="video.movie"
                   class="w-fit text-xs rounded-lg px-1 uppercase bg-purple-800 text-white font-semibold hover:cursor-pointer">
                Movie
              </div>
              <div v-if="video.movieTrailer"
                   class="w-fit text-xs rounded-lg px-1 uppercase bg-indigo-800 text-white font-semibold">Trailer
              </div>
              <div v-if="video.newsStory"
                   class="hidden w-fit text-xs rounded-lg px-1 uppercase bg-orange-800 text-white font-semibold">News
              </div>
              <div v-if="video.storage_location === 'external'"
                   class="hidden w-fit text-xs rounded-lg px-1 uppercase bg-blue-800 text-white font-semibold">External
              </div>
            </div>
            <span v-if="!video.can.view" class="font-semibold text-red-700">You are currently unable to view this video. Please check with the admin.</span>
            <div v-show="video.upload_status === 'processing'"
                 class="ml-2 mb-1 py-1 px-2 bg-gray-600 text-gray-50 text-xs w-fit rounded-lg inline">Processing
            </div>
          </div>
          <div
              scope="row"
              class="hidden md:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
          >
            <span>{{ video.size }}</span>
          </div>
          <div class="table-cell px-6 py-4">
            <span>{{ formatDate(video.created_at) }}</span>
          </div>
          <div>
            <Link :href="`#`">
              <button
                  @click.prevent="deleteVideo(video)"
                  class="px-3 py-2 mb-2 mr-4 font-xs text-white bg-red-600 hover:bg-red-500 rounded-lg"
              >Delete
              </button>
            </Link>
          </div>

        </div>
      </div>
    </div>
    <!-- Paginator -->
    <Pagination :data="videos" class="pb-6"/>
  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useNowPlayingStore } from "@/Stores/NowPlayingStore"
import { useUserStore } from "@/Stores/UserStore"
import Pagination from "@/Components/Global/Paginators/Pagination"

const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()

let props = defineProps({
  videos: Object,
  can: Object,
})

const playVideo = (video) => {
  nowPlayingStore.reset()
  // if file exists and is not processing, play file.
  if (video.file_name !== '' && video.upload_status !== 'processing') {

    // Check if showEpisode exists
    if (video.showEpisode?.show) {
      nowPlayingStore.activeMedia.details.secondaryName = video.showEpisode.show.name
      nowPlayingStore.activeMedia.details.secondaryUrl = 'shows/' + video.showEpisode.show.slug
      nowPlayingStore.activeMedia.details.primaryName = video.showEpisode.data.name
      nowPlayingStore.activeMedia.details.primaryUrl = 'shows/' + video.showEpisode.show.slug + '/episode/' + video.showEpisode.data.slug
    }
    // Check if movie exists
    if (video.movie) {
      nowPlayingStore.activeMedia.details.primaryName = video.movie.name
      nowPlayingStore.activeMedia.details.primaryUrl = 'movies/' + video.movie.slug
    }

    // Check if movieTrailer exists
    if (video.movieTrailer) {
      nowPlayingStore.activeMedia.details.primaryName = video.movieTrailer.name
      nowPlayingStore.activeMedia.details.primaryUrl = 'movies/' + video.movieTrailer.slug
    }
    // Check if newsStory exists
    if (video.newsStory) {
      nowPlayingStore.activeMedia.details.primaryName = props.video.name
      nowPlayingStore.activeMedia.details.primaryUrl = 'movies/' + video.movie.slug
    }
    // console.log('load new source from file')
    // videoPlayerStore.loadNewSourceFromFile(video)
    videoPlayerStore.prepareForNewVideoSource(video)
    nowPlayingStore.activeMedia.details.description = 'Filename: ' + video.file_name
    // videoPlayerStore.makeVideoFullPage()
    appSettingStore.ott = 1
    userStore.showNavDropdown = false
    userStore.prevUrl = window.history.state.url
    // Inertia.visit('/stream')

  }

};


function reload() {
  Inertia.reload({
    only: ['videos'],
  });
}

function deleteVideo($video) {
  if (confirm('Are you sure you want to delete this video? This action is not reversible and may have' +
      ' devastating effects on the database.')) {
    Inertia.post('/video/delete', {'videoId': $video.id});
  }
}

</script>

<style scoped>
#tooltip {
  background: #333;
  color: white;
  font-weight: bold;
  padding: 4px 8px;
  font-size: 13px;
  border-radius: 4px;
}

:deep(.popper) {
  background: #333333;
  padding: 2px;
  border-radius: 20px;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
}

:deep(.popper #arrow::before) {
  background: #333333;
}

:deep(.popper:hover),
:deep(.popper:hover > #arrow::before) {
  background: #333333;
}
</style>
