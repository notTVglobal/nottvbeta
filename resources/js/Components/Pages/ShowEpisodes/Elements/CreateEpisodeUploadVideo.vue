<template>
  <div>
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="episodeVideo"
    >
      Upload Episode
    </label>
    <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">
      <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

      <ul class="pb-4 text-gray-800">
        <li>Max Video Length: <span class="text-orange-400">4 hours</span>
        </li>
        <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span>
        </li>
      </ul>

      <VideoUpload :showEpisodeId="showEpisodeStore.episode.id" class="text-black" @upload-start="handleUploadStart"
                   @upload-finished="handleUploadEnd"/>

    </div>

  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import VideoUpload from '@/Components/Global/Uploaders/VideoUpload'
import { ref } from 'vue'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  errors: Object,
})

const videoId = ref('')

function handleUploadStart() {
  showEpisodeStore.isUploading = true
}

function handleUploadEnd(id) {
  videoId.value = id
  console.log(`Received videoId: ${videoId.value}`)
  showEpisodeStore.isUploading = false
}
</script>