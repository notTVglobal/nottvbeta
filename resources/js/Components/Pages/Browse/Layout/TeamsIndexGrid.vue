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
            :space-between="30"
            :centered-slides="true"
            :autoplay="{ delay: 2500, disableOnInteraction: false }"
            :pagination="{ clickable: true }"
            :navigation="true"
            :modules="modules"
            class="mySwiper"
        >
          <swiper-slide v-for="team in browseStore.teams" :key="team.id" class="p-8">
            <div
                class="swiper-buffer"
                @click="navigateToTeam(team.slug)"
                @mouseenter="pauseAutoplay"
                @mouseleave="resumeAutoplay"
                @touchstart="pauseAutoplay"
                @touchend="resumeAutoplay"
            >
              <div
                  class="swiper-item cursor-pointer bg-white rounded-lg shadow-lg p-4 hover:shadow-xl transition duration-300 relative group"
              >
                <SingleImage :image="team.image" :alt="'Team Logo'" class="rounded-lg w-full object-cover h-64 mb-4" />
                <h3 class="text-2xl font-bold text-center text-gray-800 transition group-hover:text-blue-500">{{ team.name }}</h3>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-0 group-hover:opacity-75 transition duration-300"></div>
              </div>
            </div>
          </swiper-slide>
        </swiper>
      </div>
    </transition>

    <transition name="fade">
      <div v-if="search" class="w-full flex flex-wrap justify-center gap-4 mt-6">
        <div @click="navigateToTeam(team.slug)" v-for="team in filteredTeams" :key="team.id" class="team-card bg-white rounded-lg shadow-md p-4 hover:shadow-lg hover:cursor-pointer transition duration-300 flex flex-col items-center w-48">
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

    <transition name="fade">
      <div v-if="showScrollIndicator" class="scroll-indicator">
        <span>Scroll Down</span>
        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </div>
    </transition>

    <!-- Target element to detect visibility -->
    <div ref="target">
      <h1 class="invisible">Bottom Marker</h1>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import throttle from 'lodash/throttle'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'swiper/css/autoplay'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import { useBrowseStore } from '@/Stores/BrowseStore'
import { router } from '@inertiajs/vue3'
import { useElementVisibility } from '@vueuse/core'

const browseStore = useBrowseStore()

const search = ref(browseStore.filters.search)
const loading = ref(true)
const showScrollIndicator = ref(false)

const { currentPage, lastPage, fetchTeams } = browseStore

const modules = [Autoplay, Pagination, Navigation]

const target = ref(null)
const targetIsVisible = useElementVisibility(target)

watch(targetIsVisible, (isVisible) => {
  showScrollIndicator.value = !isVisible
})

watch(search, throttle(function (value) {
  browseStore.filters.search = value
  fetchTeams(teamStore.currentPage)
}, 300))

const filteredTeams = computed(() => {
  if (!search.value) return []
  return browseStore.teams.filter(team => team.name.toLowerCase().includes(search.value.toLowerCase()))
})

const loadMoreTeams = () => {
  browseStore.currentPage ++
  fetchTeams(browseStore.currentPage)
}

const navigateToTeam = (slug) => {
  router.visit(`/teams/${slug}`)
}

const pauseAutoplay = () => {
  const swiper = document.querySelector('.mySwiper').swiper
  swiper.autoplay.stop()
}

const resumeAutoplay = () => {
  const swiper = document.querySelector('.mySwiper').swiper
  swiper.autoplay.start()
}

onMounted(() => {
  browseStore.clearSearch()
  search.value = ''
  fetchTeams().then(() => {
    loading.value = false
  })
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

.scroll-indicator {
  position: fixed;
  bottom: 20px;
  left: 20px;
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.9);
  padding: 10px 20px;
  border-radius: 30px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  color: #ff5722;
  font-size: 1.2rem;
  font-weight: bold;
  z-index: 1000;
  animation: bounce 1.2s infinite ease-in-out;
}

@media (max-width: 800px) {
  .swiper-buffer {
    padding: 20px;
  }

  .scroll-indicator {
    display: none;
  }
}
</style>
