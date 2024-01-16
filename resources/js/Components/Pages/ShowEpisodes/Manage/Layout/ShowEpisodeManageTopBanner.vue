<template>
  <div class="font-bold text-xl p-4 my-4 w-full text-center"
       :class="goLiveBanner">
    <div v-if="!teamStore.goLiveDisplay">
      <div v-if="episodeStatus.id < 6" class="text-white uppercase hidden">Create your episode</div>
      <div v-if="episodeStatus.name"><span class="uppercase text-xs text-white">Status:</span> <span
          :class="episodeStatusClass">{{ episodeStatus.name }}</span></div>
    </div>
    <div v-else>
      <span>You are about to go live.</span>
      <!--                    if the episode is scheduled to go live then display the next line instead -->
      <!--                    <span v-if="episode.scheduled_release > now">You are scheduled to go live.</span>-->
      <!--                    <span v-if="episode.isLive">You are now live.</span>-->

    </div>
  </div>
</template>

<script setup>
import { computed } from "vue"
import { useTeamStore } from "@/Stores/TeamStore"

const teamStore = useTeamStore()

let props = defineProps({
  episode: Object,
  episodeStatus: Object,
})

const goLiveBanner = computed(() => ({
  'bg-red-500 text-white': teamStore.goLiveDisplay,
  'bg-black text-red-600': !teamStore.goLiveDisplay
}))

const episodeStatusClass = computed(() => ({
  'font-semibold text-xl text-orange-400': props.episodeStatus.id === 1,
  'text-xl text-green-400': props.episodeStatus.id === 2,
  'font-semibold text-xl text-green-600': props.episodeStatus.id === 3,
  'font-bold text-xl text-green-600': props.episodeStatus.id === 4,
  'font-semibold text-xl text-purple-700': props.episodeStatus.id === 5,
  'font-italic text-xl text-pink-500': props.episodeStatus.id === 6,
  'font-bold text-xl light:text-black dark:text-white': props.episodeStatus.id === 7,
  'font-medium text-xl text-gray-500': props.episodeStatus.id === 8,
  'font-semibold font-italic text-xl text-red-700': props.episodeStatus.id === 9,
  'font-bold text-xl text-red-800': props.episodeStatus.id === 10,
}))
</script>

<style scoped>

.episodeManageBanner {
  @apply bg-black text-red-600;
}

</style>
