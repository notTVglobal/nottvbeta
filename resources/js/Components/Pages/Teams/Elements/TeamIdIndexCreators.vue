<template>
  <div class="creators">
    <div class="w-full bg-gray-900 text-white text-center tracking-wider text-2xl p-4 mb-8">CONTRIBUTORS</div>
    <!-- Note: Team Members ("Creators") will now be hidden by default.
    Teams and creators need to opt-in to have public creator profiles
    and to be visible on Team, Shows, and Episode pages. This change
    is to enhance privacy and give more control to the individuals involved. -->

    <div class="flex flex-wrap justify-center bg-gray-800 px-4 pt-2 pb-8 rounded-lg w-full mx-auto mb-8">
      <div v-for="creator in creators.data" :key="creator.id" class="p-4 ">
        <div class="flex flex-col items-center">
          <div>
            <img v-if="creator.profile_photo_path"
                 :src="'/storage/' + creator.profile_photo_path"
                 :alt="creator.name + ' profile photo'"
                 class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
            <img v-else-if="creator.profile_photo_url"
                 :src="creator.profile_photo_url"
                 alt="creator.name"
                 class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
            <img v-else
                 src="/storage/images/Ping.png"
                 alt="no profile photo, using our ping logo as a placeholder"
                 class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
          </div>
          <div class="text-center min-w-20 ">
            <span class="text-gray-50">{{ creator.name }}</span>
          </div>
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