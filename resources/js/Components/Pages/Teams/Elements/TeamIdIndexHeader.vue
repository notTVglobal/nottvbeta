<template>
  <header class="flex flex-col justify-between pt-2 lg:pt-6 px-5">
    <div class="flex flex-row flex-wrap justify-between gap-2">
      <div class="flex flex-row w-full justify-center lg:justify-end pb-6 lg:pb-0">
        <button @click="Inertia.visit('/teams')" class="btn btn-wide">Browse All Teams</button>
      </div>
      <div class="flex flex-row gap-2">
        <SingleImage :image="image" :alt="'team logo'" :class="'w-40 mr-4'"/>
        <h3 class="light:text-gray-900 dark:text-gray-50 inline-flex items-center text-3xl font-semibold relative uppercase">
          {{ team.name }}
        </h3>
      </div>

    </div>
    <SocialMediaBadgeLinks :socialMediaLinks="team.socialMediaLinks"/>
  </header>
</template>
<script setup>
import { Inertia } from '@inertiajs/inertia'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'

const props = defineProps({
  team: Object,
  image: Object,
})
</script>