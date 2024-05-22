<template>
  <div>
    <div v-if="errors.name" v-text="errors.name"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
    <div v-if="errors.description" v-text="errors.description"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
    <div v-if="errors.episode_number" v-text="errors.episode_number"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
    <div v-if="errors.notes" v-text="errors.notes"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
    <div v-if="errors.video_file_url" v-text="errors.video_file_url"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
    <!--                            <div v-if="errors.youtube_url" v-text="errors.youtube_url"-->
    <!--                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->
    <div v-if="errors.video_embed_code" v-text="errors.video_embed_code"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
  </div>
</template>
<script setup>
const props = defineProps({
  errors: Object,
})
</script>