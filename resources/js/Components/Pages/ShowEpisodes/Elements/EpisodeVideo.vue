<template>
  <div>
    <div
        v-if="episode.video?.storage_location === 'external'"
        class="flex justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5">

      <div v-if="episode.video?.video_url === ''">NO VIDEO</div>
      <video id="episodeEditVideoPlayer" v-if="episode.video?.video_url"
             class="video-js w-fit" controls>
        <source :src="`${episode.video?.video_url}`" :type="`${episode.video?.type}`">
      </video>

      <!--                    <iframe v-if="episode.video_url"-->
      <!--                            class="rumble" width="w-fit" height="" :src="`${episode.video_url}`" frameborder="0" allowfullscreen>-->
      <!--                    </iframe>-->
    </div>

    <div v-if="!episode?.video_id"
         class="flex justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5 mt-6">
      NO VIDEO</div>
    <div v-else class="flex flex-col justify-center text-center mb-6 bg-black w-full px-6 py-3">
      <div class="block mb-2 uppercase font-bold text-xs text-white"
           for="name"
      >
        Episode Video
      </div>
      <div
          v-if="episode.video?.upload_status === 'processing'"
          class="text-center place-self-center font-semibold text-xl">Video processing...
      </div>

      <video v-if="episode.video?.storage_location === 'spaces' && episode.video?.upload_status !== 'processing'"
             id="episodeEditPlayer"
             :src="episode.video?.cdn_endpoint+episode.video?.cloud_folder+episode?.video.folder+'/'+episode.video?.file_name"
             controls></video>
    </div>
  </div>

</template>

<script setup>
defineProps({
  episode: Object,
})
</script>