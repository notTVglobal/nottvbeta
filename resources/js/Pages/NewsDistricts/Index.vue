<template>
  <Head :title="pageTitle"/>
  <div id="topDiv"></div>
  <div class="flex flex-col min-h-screen bg-gray-50 text-black w-full overflow-x-hidden" :class="marginTopClass">


    <header class="place-self-center flex flex-col w-full text-black bg-gray-800 text-gray-50">
      <PublicNewsNavigationButtons :can="can"/>

    </header>

    <PublicNavigationMenu v-if="!userStore.loggedIn" class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu v-if="!userStore.loggedIn"/>

    <section class="mb-2">
      <div class="pt-10 flex w-full h-fit flex-wrap justify-between align-baseline gap-x-10 gap-y-2">
        <div><h2 class="text-center text-xl md:text-3xl font-semibold my-auto align-middle pl-6">
          <span>{{ districtTypeText }}</span>
        </h2></div>

        <div class="flex flex-col justify-end items-end gap-x-2 gap-y-2">
          <div class="my-auto pr-6">
            <div class="w-full flex flex-row flex-wrap justify-end px-6 gap-2 relative">
              <input v-model="search" type="search" class="bg-gray-50 text-black text-md rounded-full
                        focus:outline-none focus:shadow w-64 pl-10 pr-3 py-1" placeholder="Search...">
              <div class="absolute inset-y-0 left-8 flex items-center pl-2">
                <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
                </svg>
              </div>
            </div>
          </div>


          <div class="my-auto pr-6">
            <div class="w-full flex flex-row flex-wrap justify-end px-6 gap-2">
              <div>
                <button
                    v-if="can.viewNewsroom"
                    @click="appSettingStore.btnRedirect(`/newsroom`)"
                    class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"

                >Newsroom
                </button>
              </div>
              <div>
                <button
                    @click="appSettingStore.btnRedirect(``)"
                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"
                    disabled
                >Upload Press Release
                </button>
              </div>
            </div>

          </div>

        </div>


      </div>
    </section>


    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 pb-4 border-b border-gray-800 flex justify-center">

        <div v-if="can.viewNewsroom" class="districts-page">
          <SelectDistrictTypeAndProvince :can="can"/>
          <MapCanada v-if="!selectedProvinceId"/>
          <MapAlbertaFederalDistrictsSvg v-if="districtType === 'federal' && selectedProvinceId === 2"/>
          <MapAlbertaExampleSubnationalSvg v-if="districtType === 'subnational' && selectedProvinceId === 2"/>
          <FederalDistrictLayout v-if="districtType === 'federal' && selectedProvinceId"/>
          <SubnationalDistrictLayout v-if="districtType === 'subnational' && selectedProvinceId"/>

        </div>



        <div v-if="!can.viewNewsroom">
          <div class="under-construction">

            <!-- Or Using Font Awesome Icon -->
            <font-awesome-icon icon="fa-wrench" class="mr-2 fa-wrench" aria-hidden="true"/>
            <!-- <i class="fa fa-wrench" aria-hidden="true"></i> -->

            <p class="message">This page is currently under construction. Check back soon!</p>
          </div>
        </div>


      </div>
    </main>


    <Footer v-if="!userStore.loggedIn"/>

  </div>
</template>

<script setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNewsDistrictStore } from '@/Stores/NewsDistrictStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { usePage } from '@inertiajs/vue3'
import throttle from 'lodash/throttle'
import { router } from '@inertiajs/vue3'
import SelectDistrictTypeAndProvince from '@/Components/Pages/NewsDistricts/SelectDistrictTypeAndProvince.vue'
import FederalDistrictLayout from '@/Components/Pages/NewsDistricts/FederalDistrictLayout.vue'
import SubnationalDistrictLayout from '@/Components/Pages/NewsDistricts/SubnationalDistrictLayout.vue'
import MapCanada from '@/Components/Pages/NewsDistricts/MapCanada.vue'
import MapAlbertaExampleSubnationalSvg from '@/Components/Global/Maps/Canada/MapAlbertaExampleSubnationalSvg.vue'
import MapAlbertaFederalDistrictsSvg from '@/Components/Global/Maps/Canada/MapAlbertaFederalDistrictsSvg.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()
const newsDistrictStore = useNewsDistrictStore()

const {pageProps} = usePage()

appSettingStore.currentPage = 'newsDistricts.index'
appSettingStore.setPrevUrl()

let props = defineProps({
  // newsStories: Object,
  federalDistricts: Object,
  subnationalDistricts: Object,
  provinces: Object,
  filters: Object,
  can: Object,
})

// Accessing state as a computed property
const districtType = computed(() => newsDistrictStore.districtType);
const districtName = computed(() => newsDistrictStore.districtName);
const selectedProvinceId = computed(() => newsDistrictStore.selectedProvinceId);

const districtTypeText = computed(() => {
  return districtType.value === 'federal' ? 'Federal Districts' : 'Subnational Districts';
});

watch(selectedProvinceId, (newValue) => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
});

// Computed property for dynamically setting the page title
const pageTitle = computed(() => {
  // If districtName has a value, return it; otherwise, fall back to districtTypeText
  return districtName.value.trim() !== '' ? districtName.value : districtTypeText.value;
});

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
  appSettingStore.currentPage = 'newsDistricts.index'

  newsDistrictStore.federalDistricts = props.federalDistricts
  newsDistrictStore.subnationalDistricts = props.subnationalDistricts
  newsDistrictStore.provinces = props.provinces

  if (userStore.isMobile || window.innerWidth < 1024 || appSettingStore.fullPage) {
    appSettingStore.ott = 0
  } else {
    appSettingStore.ott = 1
    appSettingStore.showOttButtons = true
  }
})

// Watch for changes in the loggedIn state of appSettingStore
watch(() => userStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn

  // Call usePageSetup if loggedIn is true
  if (loggedIn) {
    console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded)
    usePageSetup('newsDistricts.index')
  }
  nextTick(() => {
    videoPlayerStore.makeVideoTopRight()
    appSettingStore.pageIsHidden = false
  })

})

const marginTopClass = computed(() => {
  return userStore.loggedIn ? '' : 'mt-16'
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  router.get('/news-districts', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))
</script>

<style scoped>
.toggle-buttons button {
  margin-right: 10px;
  padding: 10px;
  border: none;
  background-color: #efefef;
  cursor: pointer;
}

.toggle-buttons button.active {
  background-color: #c8e6c9;
}

.under-construction {
  text-align: center;
  margin-top: 50px;
}

.under-construction .message {
  margin-top: 20px;
  font-size: 18px;
  color: #333;
}

/* Optional: Specific styles for the Font Awesome icon */
.fa-wrench {
  font-size: 50px; /* Adjust size as needed */
  color: #333; /* Adjust color as needed */
}

/* Additional styling as needed */
</style>
