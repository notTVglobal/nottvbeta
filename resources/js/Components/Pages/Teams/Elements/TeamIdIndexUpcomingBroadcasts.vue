<template>
  <div v-if="broadcasts.length" class="accordion bg-gray-800 text-gray-50 p-5 rounded-lg shadow">
    <!-- Header for the Accordion -->
    <h2 class="text-xl font-semibold mb-4">More Upcoming Broadcasts</h2>

    <div v-for="(broadcast, index) in visibleBroadcasts" :key="index" class="accordion-item mb-2 text-yellow-300"
         :ref="el => setAccordionItem(el, index)"
    >
      <button
          @click="toggle(index)"
          class="accordion-button flex flex-col sm:flex-row justify-between items-center text-center sm:text-left w-full py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded-lg focus:outline-none">
        <span class="block sm:inline mt-2 sm:mt-0">{{ dayjs(broadcast.broadcastDate).format('dddd, MMMM D') }}</span>
        <span class="block sm:inline text-white text-xl">{{ broadcast.name }}</span>
        <svg :class="{'transform rotate-180': activeIndex === index}" xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div v-if="activeIndex === index" class="accordion-content bg-gray-600 rounded-b-lg p-4">
        <div class="flex flex-row flex-wrap-reverse justify-center items-end gap-4">
          <SingleImage :image="broadcast.image" :alt="`Broadcast Image`"
                       :class="`max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto mb-4 sm:mb-0 sm:mr-4 object-cover rounded-lg`"/>
          <div class="text-left">
            <p class="text-white dark:text-white">{{ dayjs(broadcast.broadcastDate).format('h:mm A') }}
              {{ userStore.timezoneAbbreviation }}</p>
            <p class="text-white dark:text-white">Timezone: {{ userStore.canadianTimezoneDescription }}</p>
            <p class="text-white dark:text-white">{{ dayjs(broadcast.broadcastDate).format('dddd, MMMM D') }}</p>
            <p class="font-semibold uppercase tracking-wider text-yellow-600">Category: {{
                broadcast?.category?.name
              }}</p>
            <p class="font-semibold tracking-wide text-yellow-500">Subcategory: {{ broadcast?.subCategory?.name }}</p>
            <button @click="goToBroadcast(broadcast)"
                    class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mx-auto">
              Go to Page
            </button>
            <ExpandableDescription :description="broadcast.description" :hideTitle="true"
                                   :class="`text-white dark:text-white pt-6 max-w-96`"/>
          </div>
        </div>
      </div>
    </div>
    <div class="flex w-full justify-center">
      <button v-if="visibleBroadcasts.length < broadcasts.length" @click="showMore"
              class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mx-auto"> Show More
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'
import dayjs from 'dayjs'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import { useUserStore } from '@/Stores/UserStore'
import { Inertia } from '@inertiajs/inertia'

const userStore = useUserStore()

// Props
const props = defineProps({
  broadcasts: Object,
})

// States
const activeIndex = ref(null)
const visibleCount = ref(6)
const accordionItems = ref([]); // To store references to accordion items

// Computed Properties
const visibleBroadcasts = computed(() => {
  return props.broadcasts.slice(0, visibleCount.value)
})

// Methods
const setAccordionItem = (el, index) => {
  accordionItems.value[index] = el;
};


const toggle = async (index) => {
  activeIndex.value = activeIndex.value === index ? null : index;
  if (activeIndex.value !== null) {
    await nextTick();
    accordionItems.value[index]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
};

const showMore = () => {
  visibleCount.value += 6
}

const goToBroadcast = (broadcast) => {
  console.log(broadcast)
  if (!broadcast.type) return
  const baseLink = {
    'show': `/shows/${broadcast.slug}/`,
    'movie': `/movie/${broadcast.slug}/`,
    'showEpisode': `/shows/${broadcast?.show?.slug}/episode/${broadcast.slug}/`,
  }
  const url = baseLink[broadcast.type] || '/'
  Inertia.visit(url)
}
</script>

