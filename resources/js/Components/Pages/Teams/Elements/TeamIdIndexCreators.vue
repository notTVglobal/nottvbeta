<template>
  <div class="creators">
    <div class="w-full bg-gray-900 text-white text-2xl p-4 mb-8">CREATORS</div>
    <!-- Note: Team Members ("Creators") will now be hidden by default.
    Teams and creators need to opt-in to have public creator profiles
    and to be visible on Team, Shows, and Episode pages. This change
    is to enhance privacy and give more control to the individuals involved. -->

    <div class="flex flex-row flex-wrap justify-center">
      <div v-for="creator in creators.data" :key="creator.id" class="pb-8 bg-gray-800">
        <div class="flex flex-col items-center min-w-[8rem] px-6 py-4 font-medium break-words">
          <img v-if="!creator.profile_photo_path && creator.profile_photo_url"
               :src="creator.profile_photo_url"
               class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2" alt="creator.name">
          <img v-if="creator.profile_photo_path" :src="'/storage/' + creator.profile_photo_path"
               :alt="creator.name + ' profile photo'"
               class="rounded-full h-32 w-32 min-h-32 min-w-32 object-cover mb-2">
          <img v-else :src="'/storage/images/Ping.png'"
               :alt="'no profile photo, using our ping logo as a placeholder'"
               class="rounded-full h-32 w-32 min-h-32 min-w-32 object-cover mb-2">
          <span class="text-gray-50 text-center">{{ creator.name }}</span>
        </div>
      </div>
    </div>

  </div>
</template>
<script setup>
defineProps({
  creators: Object
})

</script>
<style scoped>
.description {
  overflow: hidden;
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  text-overflow: ellipsis;
  @apply tracking-wide
}
</style>