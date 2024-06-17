<template>

  <div class="relative min-h-96 overflow-x-auto shadow-md sm:rounded-lg">
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
                <div class="text-xs z-999" id="tooltip">
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
          <div class="flex gap-2">
            <div>
              <button
                  @click.prevent="beginDownload(video)"
                  class="px-2 py-1 text-white bg-orange-600 hover:bg-orange-500 rounded-lg flex items-center"
              >
                <font-awesome-icon icon="download" class="mr-2" />
                Download
              </button>
            </div>
            <div>
              <button
                  @click.prevent="openShareUrl(video)"
                  class="px-2 py-1 text-white bg-blue-600 hover:bg-blue-500 rounded-lg flex items-center"
              >
                <font-awesome-icon icon="share-alt" class="mr-2" />
                Share
              </button>
            </div>
            <div>
              <button
                  @click.prevent="deleteVideo(video)"
                  class="px-2 py-1 text-white bg-red-600 hover:bg-red-500 rounded-lg flex items-center"
              >
                <font-awesome-icon icon="trash-can" class="mr-2" />
                Delete
              </button>
            </div>
          </div>


        </div>
      </div>
    </div>
    <!-- Paginator -->
    <Pagination :data="videos" class="pb-6"/>
  </div>

</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useNowPlayingStore } from "@/Stores/NowPlayingStore"
import { useUserStore } from "@/Stores/UserStore"
import Pagination from "@/Components/Global/Paginators/Pagination"
import CopyClipboard from '@/Components/Global/Text/CopyClipboard.vue'
import { ref } from 'vue'

const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()

const page = usePage().props

let props = defineProps({
  videos: Object,
  can: Object,
})

const videoShareUrl = (video) => {
  return `${page.appUrl}/video/${video.ulid}`;
  // return video.cdn_endpoint + video.cloud_private_folder + video.folder + video.file_name;
};

const openShareUrl = (video) => {
  const url = videoShareUrl(video);
  window.open(url, '_blank');
};

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

    if (!appSettingStore.isSmallScreen) {
      appSettingStore.ott = 1
    }

    userStore.showNavDropdown = false
    userStore.prevUrl = window.history.state.url
    // router.visit('/stream')

  }

};

const beginDownload = async (video) => {
  const url = video.download_filename;

  try {
    // // Fetch the file from the URL
    // const response = await fetch(url, {
    //   method: 'GET',
    //   headers: {
    //     'Content-Type': 'application/octet-stream'
    //   }
    // });
    //
    // // Ensure the response is OK
    // if (!response.ok) {
    //   console.log('Network response was not ok');
    //   return;
    // }
    //
    // // Get the response as a Blob
    // const blob = await response.blob();

    // Fetch the file from the URL
    const response = await fetch(url)
    const blob = await response.blob()

    // Create a link element
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(blob);
    downloadLink.download = video.download_filename || 'downloaded_video'; // Ensure the filename has an extension

    // Append the link to the document body
    document.body.appendChild(downloadLink);

    // Trigger the download
    downloadLink.click();

    // Remove the link from the document
    document.body.removeChild(downloadLink);

    // Revoke the object URL
    URL.revokeObjectURL(downloadLink.href);
  } catch (error) {
    console.error('Download failed', error);
  }
}

function reload() {
  router.reload({
    only: ['videos'],
  });
}

function deleteVideo($video) {
  if (confirm('Are you sure you want to delete this video? This action is not reversible.')) {
    router.post('/video/delete', {'videoId': $video.id});
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
