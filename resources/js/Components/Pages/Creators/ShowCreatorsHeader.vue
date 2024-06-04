<template>
  <div>
    <div class="flex justify-end items-center w-full mb-6 gap-2">
      <ShareButton v-if="creator" :model="creator" />
      <BackButton />
    </div>

    <div :class="descriptionFlexClass" class="flex flex-wrap items-center justify-center lg:justify-start gap-8 mb-12">
      <img v-if="creator && creator?.profile_photo_path"
           :src="'/storage/' + creator?.profile_photo_path"
           class="rounded-full h-64 w-64 object-cover">
      <img v-else
           :src="creator?.profile_photo_url"
           class="rounded-full h-64 w-64 object-cover bg-gray-300">
      <div class="flex flex-col items-center lg:items-start">
        <span class="text-5xl mb-4">{{ creator.name }}</span>
        <p class="text-lg text-gray-700 mb-4"><ExpandableDescription :description="creator?.description" :hideTitle="true"/></p>
        <SocialMediaBadges v-if="creator?.social_links" :links="creator?.social_links" class="mt-4" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import BackButton from '@/Components/Global/Buttons/BackButton.vue';
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import SocialMediaBadges from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'

const props = defineProps({
  creator: Object,
});

const page = usePage()

const descriptionFlexClass = computed(() => {
  return page.props.auth.user ? 'flex-col xl:flex-row items-center' : 'flex-col lg:flex-row items-center';
});
</script>

<style scoped>
/* Tailwind CSS styles */
.text-gray-700 {
  color: #374151; /* Medium gray text color */
}
</style>
