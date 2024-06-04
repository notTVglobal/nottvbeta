<template>
  <!-- Teams Section -->
  <div class="w-full mb-6">
    <h2 class="text-3xl font-bold pb-3 text-center">Teams</h2>
    <div class="border-2 border-blue-500 rounded-lg p-4 bg-gray-100 shadow-lg">
      <div v-if="loading" class="flex w-full justify-center">
        <span class="loading loading-ball text-lg text-info" />
      </div>
      <div v-else>
        <swiper
            :slides-per-view="1"
            :breakpoints="{
              660: { slidesPerView: 1, spaceBetween: 8 },
              1024: { slidesPerView: 3, spaceBetween: 8 }
            }"
            navigation
            class="mySwiper"
        >
          <swiper-slide v-for="team in teams" :key="team.id" class="p-8">
            <div @click="toggleTeam(team.id)"
                 class="cursor-pointer bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition duration-300">
              <SingleImage :image="team.image" :alt="`Team Logo`" class="rounded-lg h-48 w-48 object-cover mb-2"/>
              <h3 class="text-lg font-semibold text-center">{{ team.name }}</h3>
            </div>
            <div v-if="expandedTeam === team.id" class="mt-4">
              <div class="shows">
                <p class="font-semibold text-xl text-center text-blue-600 mb-2">Shows:</p>
                <div class="flex flex-col gap-4">
                  <div v-for="show in team.shows" @click.prevent="appSettingStore.btnRedirect(`/shows/${show.slug}`)"
                       :key="show.id"
                       class="flex gap-4 items-center group bg-white p-4 rounded-lg shadow-md hover:bg-gray-100 hover:cursor-pointer hover:shadow-lg transition duration-300">
                    <SingleImage :image="show.image" :alt="`Show Image`"
                                 class="rounded-lg h-16 w-16 object-cover group-hover:shadow-lg transition duration-300 ease-in-out"/>
                    <span class="text-lg font-semibold text-gray-700 group-hover:text-blue-500">{{ show.name }}</span>
                  </div>
                </div>
              </div>
              <Link :href="`/teams/${team.slug}`"
                    class="text-blue-500 hover:text-blue-400 hover:underline hover:cursor-pointer text-sm mt-4 block text-center">
                Go to Team Page
              </Link>
            </div>
          </swiper-slide>
        </swiper>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue'

// Import Swiper styles
import 'swiper/css'
import 'swiper/css/navigation'

// Import Swiper core and required modules
import { Navigation } from 'swiper/modules'

// Install Swiper modules
import SwiperCore from 'swiper'

SwiperCore.use([Navigation])

const props = defineProps({
  creator: Object,
})

const creatorSlug = props.creator.slug

const teams = ref(null)
const expandedTeam = ref(null)

const toggleTeam = (id) => {
  expandedTeam.value = expandedTeam.value === id ? null : id
}

const loading = ref(true)

const fetchTeams = async () => {
  try {
    const response = await axios.get(`/creator/${creatorSlug}/teams`)
    teams.value = response.data
    loading.value = false
  } catch (error) {
    console.error('Error fetching teams:', error)
    loading.value = false
  }
}

const onSwiper = (swiper) => {
  console.log(swiper)
}

const onSlideChange = () => {
  console.log('slide change')
}

const modules = [Navigation]


onMounted(() => {
  fetchTeams()
})
</script>

<style scoped>
/* Tailwind CSS styles */
.mySwiper {
  padding: 5px 0;
}

.swiper-button-prev,
.swiper-button-next {
  color: #000; /* Adjust color if necessary */
}

.swiper-slide {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.swiper-slide-active {
  transform: scale(1.05);
}

.swiper-container {
  background-color: #f8fafc; /* Light background color to stand out */
  padding: 20px; /* Add some padding */
  border-radius: 8px; /* Rounded corners */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.text-center {
  text-align: center;
}

.text-3xl {
  font-size: 1.875rem; /* Increase the font size */
}

.font-bold {
  font-weight: bold;
}

/* Show styling */
.shows p {
  font-size: 1.25rem;
  font-weight: bold;
  text-align: center;
  color: #1d4ed8;
}

.shows .group {
  border: 1px solid #e5e7eb;
  background-color: #ffffff;
  border-radius: 0.375rem;
  padding: 0.75rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.shows .group:hover {
  background-color: #f3f4f6;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.shows .group-hover\:text-blue-500 {
  color: #2563eb;
}
</style>
