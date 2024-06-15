<template>
  <div class="p-6 bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 min-h-screen flex flex-col items-center">
    <h1 class="text-5xl font-extrabold text-white my-8 drop-shadow-lg tracking-wider animate-pulse">Creator Teams</h1>
    <div class="w-full max-w-3xl flex justify-center mb-8">
      <input
          v-model="search"
          type="search"
          placeholder="Search Teams..."
          class="w-full max-w-lg p-4 rounded-full border-none shadow-lg text-gray-900 bg-white"
      />
    </div>

    <transition name="fade">
      <div v-if="!search" class="w-full mb-6">
        <swiper
            :slides-per-view="1"
            :autoplay="{ delay: 2000, disableOnInteraction: false }"
            loop
            navigation
            @swiper="onSwiper"
            class="mySwiper"
        >
          <swiper-slide v-for="team in browseStore.teams" :key="team.id" class="p-8">
            <div
                class="swiper-buffer"
                @mouseenter="pauseAutoplay"
                @mouseleave="resumeAutoplay"
            >
              <div
                  @click="navigateToTeam(team.slug)"
                  @touchstart="handleTouchStart"
                  @touchend="handleTouchEnd(team.slug)"
                  class="swiper-item cursor-pointer bg-white rounded-lg shadow-lg p-4 hover:shadow-xl transition duration-300 relative group"
              >
                <SingleImage :image="team.image" :alt="'Team Logo'" class="skeleton rounded-lg w-full object-cover h-64 mb-4" />
                <h3 class="text-2xl font-bold text-center text-gray-800 transition group-hover:text-blue-500">
                  {{ team.name }}
                </h3>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-0 group-hover:opacity-75 transition duration-300"></div>
              </div>
            </div>
          </swiper-slide>
        </swiper>
      </div>
    </transition>

    <transition name="fade">
      <div v-if="search" class="w-full flex flex-wrap justify-center gap-4 mt-6">
        <div v-for="team in filteredTeams" :key="team.id" class="team-card bg-white rounded-lg shadow-md p-4 transition duration-300 flex flex-col items-center w-48">
          <SingleImage :image="team.image" :alt="'Team Logo'" class="skeleton rounded-lg h-32 w-32 object-cover mb-2" />
          <h3 class="text-lg font-semibold text-center text-gray-800">{{ team.name }}</h3>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div v-if="!search" class="w-full flex flex-wrap justify-center gap-4 mt-6">
        <div @click="navigateToTeam(team.slug)" v-for="team in browseStore.teams" :key="team.id" class="team-card bg-white rounded-lg shadow-md p-4 hover:shadow-lg hover:cursor-pointer transition duration-300 flex flex-col items-center w-48">
          <SingleImage :image="team.image" :alt="'Team Logo'" class="skeleton rounded-lg h-32 w-32 object-cover mb-2" />
          <h3 class="text-lg font-semibold text-center text-gray-800">{{ team.name }}</h3>
        </div>
      </div>
    </transition>

    <div v-if="!search && browseStore.currentPage < browseStore.lastPage" class="w-full flex justify-center mt-8">
      <button @click="loadMoreTeams" class="bg-white text-gray-800 font-bold py-3 px-6 rounded-full shadow-lg transition transform hover:scale-105">
        Load More Teams
      </button>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, watch, computed, onMounted } from 'vue'
import throttle from 'lodash/throttle'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/autoplay'
import { Navigation, Autoplay } from 'swiper/modules'
import SwiperCore from 'swiper'
import { useBrowseStore } from '@/Stores/BrowseStore'

SwiperCore.use([Navigation, Autoplay])

const browseStore = useBrowseStore()

const search = ref(browseStore.filters.search)
const loading = ref(true)
let swiperInstance = null

const { currentPage, lastPage, isLoading, fetchTeams } = browseStore

watch(search, throttle(function (value) {
  browseStore.filters.search = value
  fetchTeams(1)
}, 300))

const filteredTeams = computed(() => {
  if (!search.value) return []
  return browseStore.teams.filter(team => team.name.toLowerCase().includes(search.value.toLowerCase()))
})

const loadMoreTeams = () => {
  fetchTeams(currentPage + 1)
}

const onSwiper = (swiper) => {
  swiperInstance = swiper
}

const pauseAutoplay = () => {
  if (swiperInstance) {
    swiperInstance.autoplay.stop()
  }
}

const resumeAutoplay = () => {
  if (swiperInstance) {
    swiperInstance.autoplay.start()
  }
}

const handleTouchStart = (event) => {
  pauseAutoplay()
  event.stopPropagation()
}

const handleTouchEnd = (slug) => {
  return (event) => {
    resumeAutoplay()
    // Ensure it's a click and not a hold
    if (!event.touches || event.touches.length === 0) {
      router.visit(`/teams/${slug}`)
    }
  }
}

const navigateToTeam = (slug) => {
  router.visit(`/teams/${slug}`)
}

onMounted(() => {
  fetchTeams()
  loading.value = false
})
</script>

<style scoped>
.mySwiper {
  padding: 10px 0;
}

.swiper-button-prev,
.swiper-button-next {
  color: #fff;
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
  background-color: #f8fafc;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.loading-ball {
  animation: bounce 1.2s infinite ease-in-out;
  background-color: #3490dc;
  border-radius: 100%;
  height: 1em;
  width: 1em;
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-15px);
  }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */
{
  opacity: 0;
}

.swiper-buffer {
  padding: 20px 100px;
  border-radius: 10px;
  transition: box-shadow 0.3s ease-in-out;
}

.swiper-buffer:hover {
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
}

.team-card {
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.team-card:hover {
  transform: scale(1.05);
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
}

@media (max-width: 800px) {
  .swiper-buffer {
    padding: 20px;
  }
}
</style>
