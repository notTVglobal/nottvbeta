<template>
  <div>
    <!-- Conditional rendering for the video section -->
      <!-- Episode Video Label -->
      <div class="mt-6 px-5 flex justify-center w-full">
        <div class="bg-black text-white text-center uppercase font-bold text-xs p-5 mb-2 w-full">
          Episode Video
        </div>
      </div>

    <div v-if="episode?.video_id && episode.video?.storage_location === 'spaces' && episode.video?.upload_status !== 'processing'" class="">

      <!-- Video Player -->
      <video id="episodeEditPlayer"
             controls
             class="w-full h-auto"
             :src="episode.video?.cdn_endpoint + episode.video?.cloud_folder + episode?.video.folder + '/' + episode.video?.file_name">
        Your browser does not support the video tag.
      </video>
    </div>



    <!-- Processing or external storage placeholder -->
    <div v-if="episode.video?.storage_location === 'external' || episode.video?.upload_status === 'processing'" class="flex justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5">
      <div v-if="episode.video?.video_url === ''">NO VIDEO2</div>
      <video v-else id="episodeEditVideoPlayer" class="video-js w-full h-auto" controls>
        <source :src="episode.video?.video_url" :type="episode.video?.type">
      </video>
    </div>
  </div>


<!--  <div>-->
<!--    <div-->
<!--        v-if="episode.video?.storage_location === 'external'"-->
<!--        class="flex justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5">-->

<!--      <div v-if="episode.video?.video_url === ''">NO VIDEO</div>-->
<!--      <video id="episodeEditVideoPlayer" v-if="episode.video?.video_url"-->
<!--             class="video-js w-fit" controls>-->
<!--        <source :src="`${episode.video?.video_url}`" :type="`${episode.video?.type}`">-->
<!--      </video>-->

<!--      &lt;!&ndash;                    <iframe v-if="episode.video_url"&ndash;&gt;-->
<!--      &lt;!&ndash;                            class="rumble" width="w-fit" height="" :src="`${episode.video_url}`" frameborder="0" allowfullscreen>&ndash;&gt;-->
<!--      &lt;!&ndash;                    </iframe>&ndash;&gt;-->
<!--    </div>-->

<!--    <div v-if="!episode?.video_id"-->
<!--         class="flex justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5 mt-6">-->
<!--      NO VIDEO</div>-->
<!--    <div v-else class="flex flex-col justify-center text-center mb-6 bg-black w-full px-6 py-3">-->
<!--      <div class="block mb-2 uppercase font-bold text-xs text-white"-->
<!--           for="name"-->
<!--      >-->
<!--        Episode Video-->
<!--      </div>-->
<!--      <div-->
<!--          v-if="episode.video?.upload_status === 'processing'"-->
<!--          class="text-center place-self-center font-semibold text-xl">Video processing...-->
<!--      </div>-->

<!--      <video v-if="episode.video?.storage_location === 'spaces' && episode.video?.upload_status !== 'processing'"-->
<!--             id="episodeEditPlayer"-->
<!--             :src="episode.video?.cdn_endpoint+episode.video?.cloud_folder+episode?.video.folder+'/'+episode.video?.file_name"-->
<!--             controls></video>-->
<!--    </div>-->
<!--  </div>-->

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