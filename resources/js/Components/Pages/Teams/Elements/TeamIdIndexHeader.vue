<template>
  <header class="flex flex-col justify-between pt-6 px-5">
    <div class="flex flex-row flex-wrap justify-between">
      <div class="flex flex-row">
        <SingleImage :image="image" :alt="'team logo'" :class="'w-40 mr-4'"/>
        <h3 class="light:text-gray-900 dark:text-gray-50 inline-flex items-center text-3xl font-semibold relative uppercase">
          {{ team.name }}
        </h3>
      </div>
      <div>
        <button @click="Inertia.visit('/teams')" class="btn btn-wide">Browse All Teams</button>
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