<template>
  <div>
    <!-- Conditional rendering for the video section -->
    <!-- Episode Video Label -->
    <div v-if="!episode.video?.id && !episode.video?.video_url"
         class="mt-2 px-1 flex justify-center items-center w-full mx-auto bg-green-500">
      <div
          class="bg-black text-white text-center flex items-center justify-center uppercase font-bold text-xs p-5 mb-2 w-76 h-52">
        No Video
      </div>
    </div>

    <div
        v-if="episode?.video?.id && episode.video?.storage_location === 'spaces' && episode.video?.upload_status !== 'processing'"
        class="video-container">

      <!-- Video Player -->
      <video id="episodeEditPlayer"
             controls
             class="w-auto h-auto"
             :src="episode.video?.cdn_endpoint + episode.video?.cloud_folder + episode?.video.folder + '/' + episode.video?.file_name">
        Your browser does not support the video tag.
      </video>
    </div>

    <!-- Processing or external storage placeholder -->
    <div v-if="episode.video?.storage_location === 'external' || episode.video?.upload_status === 'processing'"
         class="flex justify-center w-full bg-blue-500 ml-10">
      <div class="video-container mx-auto justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5 w-fit max-h-52">
        <div v-if="episode.video?.video_url === ''">NO VIDEO</div>
        <video v-else id="episodeEditVideoPlayer" class="video-js w-fit h-auto" controls>
          <source :src="episode.video?.video_url" :type="episode.video?.type">
        </video>
      </div>
    </div>

  </div>

</template>

<script setup>
defineProps({
  episode: Object,
})
</script>

<style scoped>
.video-container {
  background-color: black; /* Black background */
  width: 100%; /* Full width of its parent */
  padding-top: 56.25%; /* Aspect ratio of 16:9 */
  position: relative; /* For absolute positioning of the video */
}

.video-container video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%; /* Video fills the width of the container */
  height: 100%; /* Video fills the height of the container */
}
</style>