<template>
  <div>
    <div class="mb-6">
      <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200"
             for="video_file_url"
      >
        Video URL (External MP4 only)
      </label>

      <input v-model="showEpisodeStore.episode.video_url"
             class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
             type="text"
             name="video_url"
             id="video_url"
      >
      <div class="text-xs mt-1">
        Example: <span class="underline">https://domainname.com/filename.mp4</span>
      </div>
      <div v-if="errors.video_url" v-text="errors.video_url"
           class="text-xs text-red-600 mt-1"></div>
    </div>

    <div class="mb-6">
      <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200"
             for="video_embed_code"
      >
        Embed Code (Rumble or Bitchute only) <span class="">*</span>
      </label>

      <TabbableTextarea v-model="showEpisodeStore.episode.video_embed_code"
                        class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                        type="text"
                        name="video_embed_code"
                        id="video_embed_code"
                        rows="10" cols="30"
      />
      <div v-if="errors.video_embed_code" v-text="errors.video_embed_code"
           class="text-xs mt-1"></div>
    </div>

    <div class="mt-2 mb-6 pb-4 border-b">
      <div class="block mb-2 uppercase text-sm font-bold dark:text-gray-200">
        * Notes about video embedding:
      </div>
      <ul class="list-decimal pb-2 ml-2">
        <li>
          If both URL and Embed Code are provided the system will attempt to get the Video Url from the
          Embed Code.
        </li>
        <li>
          We have <span class="font-bold">not</span> enabled the use of Facebook videos for security
          purposes.
        </li>
        <li>
          If you want to use YouTube, enter the YouTube video URL above in the YouTube URL field. This
          option is least preferable, due to a lower quality user experience.
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup>
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea'
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  errors: Object,
})
</script>