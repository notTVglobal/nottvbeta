<template>
    <div v-if="nextBroadcast || team.public_message" class="flex flex-col md:flex-row justify-center w-full py-2 px-5">
      <div class="flex flex-col md:flex-row bg-yellow-300 dark:bg-gray-700 w-full py-6 text-center align-middle rounded-lg shadow-lg">
        <div class="flex flex-col md:w-1/3 md:border-r border-gray-400 dark:border-gray-600 justify-center">
          <div v-if="nextBroadcast" class="px-4">
            <p class="uppercase font-bold tracking-wider text-gray-800 dark:text-gray-200">
              Next Broadcast
            </p>
            <p class="text-lg text-gray-800 dark:text-gray-200">
              {{ formattedDate }}
            </p>
            <p class="text-lg text-gray-800 dark:text-gray-200">
              {{ formattedTime }} {{ userStore.timezoneAbbreviation }}
            </p>
            <!-- Enhanced broadcast name display -->
            <a @click="goToBroadcast(nextBroadcast)" class="block mt-2 text-xl md:text-2xl font-semibold text-blue-500 dark:text-blue-300 hover:underline cursor-pointer">
              {{ nextBroadcast.name }}
            </a>
          </div>
          <div v-else class="px-4 text-gray-800 dark:text-gray-200">
            No broadcasts are currently scheduled.
          </div>
        </div>
        <div class="flex flex-col md:w-2/3 justify-center font-semibold px-4">
          <div v-if="team.public_message" class="text-lg md:text-xl leading-relaxed font-medium text-gray-800 dark:text-gray-200 p-3 mb-4 rounded">
            <span v-html="team.public_message" />
          </div>
          <div>
            <ZoomLinkButton />
          </div>
        </div>
      </div>
    </div>
  <div v-if="sortedBroadcasts.length" class="accordion bg-gray-800 text-gray-50 p-5 rounded-lg shadow">
    <!-- Header for the Accordion -->
    <h2 class="text-xl font-semibold mb-4">More Upcoming Broadcasts</h2>

    <div v-for="(broadcast, index) in sortedBroadcasts" :key="index" class="accordion-item mb-2 text-yellow-300">
      <button
          @click="toggle(index)"
          class="accordion-button flex flex-col sm:flex-row justify-between items-center text-center sm:text-left w-full py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded-lg focus:outline-none">
        <span class="block sm:inline mt-2 sm:mt-0">{{ dayjs(broadcast.broadcastDate).format('dddd, MMMM D') }}</span>
        <span class="block sm:inline text-white text-xl">{{ broadcast.name }}</span>
        <svg :class="{'transform rotate-180': activeIndex === index}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div v-if="activeIndex === index" class="accordion-content bg-gray-600 rounded-b-lg p-4">
        <div class="flex flex-row flex-wrap-reverse justify-center items-end gap-4">

          <SingleImage :image="broadcast.image" :alt="`Broadcast Image`" :class="`max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto mb-4 sm:mb-0 sm:mr-4 object-cover rounded-lg`" />
          <div class="text-left">
            <p class="text-white dark:text-white">{{ dayjs(broadcast.broadcastDate).format('h:mm A') }} {{ userStore.timezoneAbbreviation }}</p>
            <p class="text-white dark:text-white">Timezone: {{ userStore.canadianTimezoneDescription }}</p>
            <p class="text-white dark:text-white">{{ dayjs(broadcast.broadcastDate).format('dddd, MMMM D') }}</p>
            <p class="font-semibold uppercase tracking-wider text-yellow-600">Category: {{ broadcast.category.name }}</p>
            <p class="font-semibold tracking-wide text-yellow-500">Subcategory: {{ broadcast.subCategory.name }}</p>
            <button @click="goToBroadcast(broadcast)" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mx-auto">
              Go to Page
            </button>
<!--            <p class="text-white dark:text-white py-6">{{ broadcast.description }}</p>-->
            <ExpandableDescription :description="broadcast.description" :hideTitle="true" :class="`text-white dark:text-white py-6`"/>

          </div>
        </div>
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
import { computed, ref } from 'vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import ZoomLinkButton from '@/Components/Global/Buttons/ZoomLinkButton.vue'

dayjs.extend(utc)
dayjs.extend(timezone)

const userStore = useUserStore()

const props = defineProps({
  team: Object,
})



// Computes the closest broadcast to today
const nextBroadcast = computed(() => {
  if (!props.team.nextBroadcast || props.team.nextBroadcast.length === 0) {
    return null;
  }
  // Sort broadcasts by closeness to today
  const broadcasts = [...props.team.nextBroadcast].sort((a, b) =>
      Math.abs(dayjs().diff(dayjs(a.broadcastDate))) - Math.abs(dayjs().diff(dayjs(b.broadcastDate)))
  );
  return broadcasts[0]; // Return the closest broadcast
});

// Computes the sorted list of remaining broadcasts excluding the closest
const sortedBroadcasts = computed(() => {
  if (!props.team.nextBroadcast || props.team.nextBroadcast.length === 0) {
    return [];
  }
  // Clone and sort the array based on the actual broadcast date
  const broadcasts = [...props.team.nextBroadcast].sort((a, b) =>
      dayjs(a.broadcastDate).diff(dayjs(b.broadcastDate))
  );

  // Skip the first sorted item, which should be the closest broadcast
  // Check if the first sorted item is the same as 'nextBroadcast' and skip it
  if (nextBroadcast.value && broadcasts[0].id === nextBroadcast.value.id) {
    return broadcasts.slice(1); // Skip the first one
  }
  return broadcasts;
});

const activeIndex = ref(null);

const toggle = (index) => {
  if (activeIndex.value === index) {
    activeIndex.value = null;
  } else {
    activeIndex.value = index;
  }
};




const formattedDate = computed(() => {
  if (!nextBroadcast.value) return null;
  return dayjs(nextBroadcast.value.broadcastDate).tz(userStore.timezone).format('MMMM D, YYYY');
})

const formattedTime = computed(() => {
  if (!nextBroadcast.value) return null;
  return dayjs(nextBroadcast.value.broadcastDate).tz(userStore.timezone).format('h:mm a');
})

const goToBroadcast = (broadcast) => {
  if (!nextBroadcast.value) return;
  const baseLink = {
    'show': `/shows/${broadcast.slug}/`,
    'movie': `/movies/${broadcast.slug}/`,
    'showEpisode': `/shows/${broadcast?.show?.slug}/episode/${broadcast.slug}/`
  }
  const url = baseLink[broadcast.type] || '/';
  Inertia.visit(url)
}
</script>
<style scoped>
.broadcast-carousel {
  display: flex;
  overflow-x: auto;
}
.carousel-item {
  flex: 0 0 auto;
  margin-right: 20px;
  width: 300px;
}
.accordion-button {
  transition: all 0.3s ease;
}
.accordion-button svg {
  transition: transform 0.3s ease;
}
</style>