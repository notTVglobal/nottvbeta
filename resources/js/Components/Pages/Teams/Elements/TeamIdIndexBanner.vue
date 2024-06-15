<template>
  <Transition
      enter-from-class="opacity-0 transform translate-x-full"
      enter-to-class="opacity-100 transform translate-x-0"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 transform translate-x-0"
      leave-to-class="opacity-0 transform translate-x-full"
  >
    <div>
  <div v-if="teamStore.nextBroadcast || team.public_message"
       class="flex flex-col md:flex-row justify-center w-full py-2 px-5">
    <div
        class="flex flex-col md:flex-row bg-gray-600 dark:bg-gray-700 border border-gray-900 w-full py-6 text-center align-middle rounded-lg shadow-lg">
      <div class="flex flex-col md:w-1/3 md:border-r border-gray-400 dark:border-gray-600 justify-center">
        <div v-if="teamStore.nextBroadcast && !teamStore.nextBroadcastIsOver" class="px-4">
          <p class="uppercase font-bold tracking-wider text-yellow-400 dark:text-gray-200">
            Next Broadcast
          </p>
          <p class="text-lg text-gray-200 dark:text-gray-200">
            {{ formattedDate }}
          </p>
          <p class="text-lg text-gray-200 dark:text-gray-200">
            {{ formattedTime }} {{ userStore.timezoneAbbreviation }}
          </p>
          <!-- Enhanced broadcast name display -->
          <div @click="goToBroadcast(teamStore.nextBroadcast)"
             class="broadcast-hover flex items-center justify-center mx-auto mt-2 text-xl md:text-2xl font-semibold text-blue-500 dark:text-blue-300 hover:underline cursor-pointer">
            <SingleImage :image="teamStore.nextBroadcast.image" :alt="`Image`" :class="`max-w-32 max-h-24 object-cover rounded-lg`"/>
          </div>
        </div>
        <div v-else class="px-4 text-gray-400 dark:text-gray-200">
          No broadcasts are currently scheduled.
        </div>
      </div>
      <div v-if="teamStore.nextBroadcast" class="flex flex-col md:w-2/3 justify-center items-center font-semibold px-4">
        <div v-if="team.public_message"
             class="text-lg md:text-xl leading-relaxed font-medium text-gray-200 dark:text-gray-200 p-3 mb-4 rounded">
          <span v-html="team.public_message" class="public-message"/>
        </div>
        <div class="w-20 md:border-t border-gray-400 dark:border-gray-600">
        </div>

      </div>
    </div>
  </div>
      <div v-if="teamStore.nextBroadcastLoaded && teamStore.nextBroadcastZoomLink && !teamStore.nextBroadcastIsOver" class="flex justify-center text-center text-black p-4 mx-5 bg-yellow-300 rounded-lg">

        <ZoomLinkButton/>

      </div>
  <TeamIdIndexUpcomingBroadcasts/>
  <!--  <div v-if="sortedBroadcasts.length" class="accordion bg-gray-800 text-gray-50 p-5 rounded-lg shadow">-->
  <!--    &lt;!&ndash; Header for the Accordion &ndash;&gt;-->
  <!--    <h2 class="text-xl font-semibold mb-4">More Upcoming Broadcasts</h2>-->

  <!--    <div v-for="(broadcast, index) in sortedBroadcasts" :key="index" class="accordion-item mb-2 text-yellow-300">-->
  <!--      <button-->
  <!--          @click="toggle(index)"-->
  <!--          class="accordion-button flex flex-col sm:flex-row justify-between items-center text-center sm:text-left w-full py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded-lg focus:outline-none">-->
  <!--        <span class="block sm:inline mt-2 sm:mt-0">{{ dayjs(broadcast.broadcastDate).format('dddd, MMMM D') }}</span>-->
  <!--        <span class="block sm:inline text-white text-xl">{{ broadcast.name }}</span>-->
  <!--        <svg :class="{'transform rotate-180': activeIndex === index}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
  <!--          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />-->
  <!--        </svg>-->
  <!--      </button>-->
  <!--      <div v-if="activeIndex === index" class="accordion-content bg-gray-600 rounded-b-lg p-4">-->
  <!--        <div class="flex flex-row flex-wrap-reverse justify-center items-end gap-4">-->

  <!--          <SingleImage :image="broadcast.image" :alt="`Broadcast Image`" :class="`max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto mb-4 sm:mb-0 sm:mr-4 object-cover rounded-lg`" />-->
  <!--          <div class="text-left">-->
  <!--            <p class="text-white dark:text-white">{{ dayjs(broadcast.broadcastDate).format('h:mm A') }} {{ userStore.timezoneAbbreviation }}</p>-->
  <!--            <p class="text-white dark:text-white">Timezone: {{ userStore.canadianTimezoneDescription }}</p>-->
  <!--            <p class="text-white dark:text-white">{{ dayjs(broadcast.broadcastDate).format('dddd, MMMM D') }}</p>-->
  <!--            <p class="font-semibold uppercase tracking-wider text-yellow-600">Category: {{ broadcast.category.name }}</p>-->
  <!--            <p class="font-semibold tracking-wide text-yellow-500">Subcategory: {{ broadcast.subCategory.name }}</p>-->
  <!--            <button @click="goToBroadcast(broadcast)" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mx-auto">-->
  <!--              Go to Page-->
  <!--            </button>-->
  <!--&lt;!&ndash;            <p class="text-white dark:text-white py-6">{{ broadcast.description }}</p>&ndash;&gt;-->
  <!--            <ExpandableDescription :description="broadcast.description" :hideTitle="true" :class="`text-white dark:text-white py-6`"/>-->

  <!--          </div>-->
  <!--        </div>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--  </div>-->
    </div>
  </Transition>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import dayjs from 'dayjs'
import timezone from 'dayjs/plugin/timezone'
import utc from 'dayjs/plugin/utc'
import { computed, ref } from 'vue'
import ZoomLinkButton from '@/Components/Global/Buttons/ZoomLinkButton.vue'
import TeamIdIndexUpcomingBroadcasts from '@/Components/Pages/Teams/Elements/TeamIdIndexUpcomingBroadcasts.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

dayjs.extend(utc)
dayjs.extend(timezone)

const teamStore = useTeamStore()
const userStore = useUserStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {})

const activeIndex = ref(null)

const toggle = (index) => {
  if (activeIndex.value === index) {
    activeIndex.value = null
  } else {
    activeIndex.value = index
  }
}

const formattedDate = computed(() => {
  if (!teamStore.nextBroadcast) return null
  return dayjs(teamStore.nextBroadcast.broadcastDate).tz(userStore.timezone).format('dddd MMMM D, YYYY')
})

const formattedTime = computed(() => {
  if (!teamStore.nextBroadcast) return null
  return dayjs(teamStore.nextBroadcast.broadcastDate).tz(userStore.timezone).format('h:mm a')
})

const goToBroadcast = (broadcast) => {
  if (!teamStore.nextBroadcast) return
  const baseLink = {
    'show': `/shows/${broadcast.slug}/`,
    'movie': `/movie/${broadcast.slug}/`,
    'showEpisode': `/shows/${broadcast?.show?.slug}/episode/${broadcast.slug}/`,
  }
  const url = baseLink[broadcast.type] || '/'
  router.visit(url)
}
</script>
<style>
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

.broadcast-hover:hover {
  transform: translateY(-5px);
  transition: transform 0.2s;
  cursor: pointer;
}

.broadcast-hover img:hover {
  transform: scale(1.05); /* Slightly enlarge the image on hover */
  transition: transform 0.2s;
}

.public-message ul {
  list-style-type: disc;
  margin-left: 1.5em;
  padding-left: 1em;
  color: #d1d5db; /* light text color for dark mode */
}

.public-message ol {
  list-style-type: decimal;
  margin-left: 1.5em;
  padding-left: 1em;
  color: #d1d5db; /* light text color for dark mode */
}

.public-message ul li, .public-message ol li {
  margin-bottom: 0.5em;
}

.public-message a {
  color: #1E90FF; /* default blue color for links */
  text-decoration: underline;
  transition: color 0.3s, text-decoration 0.3s; /* smooth transition */
}

.public-message a:hover {
  color: #00BFFF; /* bright blue color on hover */
  text-decoration: none; /* remove underline on hover */
}

.public-message p {
  margin-bottom: 0.25em;
}

.public-message strong {
  font-weight: bold;
}

.public-message em {
  font-style: italic;
}

.public-message u {
  text-decoration: underline;
}

.public-message sub {
  vertical-align: sub;
  font-size: smaller;
}

.public-message sup {
  vertical-align: super;
  font-size: smaller;
}
</style>
