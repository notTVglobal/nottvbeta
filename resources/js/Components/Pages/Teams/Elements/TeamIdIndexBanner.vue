<template>
  <div v-if="nextBroadcast || team.public_message" class="flex flex-row justify-center w-full py-10 px-5">
    <div class="flex flex-row text-red-950 bg-yellow-300 w-full py-6 text-center align-middle">
      <div class="flex flex-col w-1/3 border-r border-black">
        <div v-if="nextBroadcast">
          <p class="uppercase font-bold tracking-wider">
            Next Broadcast
          </p>
          <p class="text-lg">
            {{ formattedDate }}
          </p>
          <p class="text-lg">
            {{ formattedTime }} {{ userStore.timezoneAbbreviation }}
          </p>
          <p class="text-lg" @click="goToBroadcast">
            <a href="#" class="cursor-pointer">{{ nextBroadcast.name }}</a>
          </p>
        </div>
        <div v-else class="px-4">
          No broadcasts are currently scheduled.
        </div>
      </div>
      <div class="flex flex-col w-2/3 justify-center font-semibold">
        {{ team.public_message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { useUserStore } from '@/Stores/UserStore'
import dayjs from 'dayjs'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'
import { computed } from 'vue'

dayjs.extend(utc)
dayjs.extend(timezone)

const userStore = useUserStore()

const props = defineProps({
  team: Object,
})

const nextBroadcast = computed(() => {
  if (!props.team.nextBroadcast || props.team.nextBroadcast.length === 0) {
    return null;
  }
  // Find the closest broadcast to today
  const sortedBroadcasts = props.team.nextBroadcast.sort((a, b) =>
      Math.abs(dayjs().diff(dayjs(a.broadcastDate))) - Math.abs(dayjs().diff(dayjs(b.broadcastDate)))
  )
  return sortedBroadcasts[0];
})

const formattedDate = computed(() => {
  if (!nextBroadcast.value) return null;
  return dayjs(nextBroadcast.value.broadcastDate).tz(userStore.timezone).format('MMMM D, YYYY');
})

const formattedTime = computed(() => {
  if (!nextBroadcast.value) return null;
  return dayjs(nextBroadcast.value.broadcastDate).tz(userStore.timezone).format('h:mm a');
})

const goToBroadcast = () => {
  if (!nextBroadcast.value) return;
  const baseLink = {
    'show': `/shows/${nextBroadcast.value.slug}/`,
    'movie': `/movies/${nextBroadcast.value.slug}/`,
    'showEpisode': `/shows/${nextBroadcast.value.show?.slug}/episode/${nextBroadcast.value.slug}/`
  }
  const url = baseLink[nextBroadcast.value.type] || '/';
  Inertia.visit(url)
}
</script>