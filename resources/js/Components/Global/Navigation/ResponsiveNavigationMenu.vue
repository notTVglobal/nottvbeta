<template>
  <div class="xl:hidden fixed top-0 w-full nav-mask border-b-2 border-gray-100">
    <div class="flex justify-between h-16 w-full bg-black nav-mask">
      <div class="flex">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
          <Link @click="navigateToStream" :href="`#`">
            <JetApplicationMark class="ml-5 block h-9 w-auto"/>
          </Link>
        </div>

        <!-- Hamburger -->
        <div class="fixed top-3 right-4 hamburgerMask">
          <div class="-mr-2 flex items-center">
            <div v-if="userStore.isCreator" class="text-yellow-600 uppercase hidden sm:block w-full">
              GOAL <span class="text-xl">100</span> subscribers
            </div>

            <div class="mx-6">
              <NotificationButton/>
            </div>

            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 transition hamburgerMask"
                :class="{ 'hover:text-white hover:bg-blue-600': appSettingStore.showNavDropdown}"

                @click="appSettingStore.toggleNavDropdown()">
              <!--                @click="chatStore.showNavDropdown = ! chatStore.showNavDropdown">-->

              <span class="pr-2">MENU</span>
              <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
              >
                <path
                    :class="appSettingStore.showNavDropdown ? 'hidden' : 'inline-flex'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    :class="appSettingStore.showNavDropdown ? 'inline-flex' : 'hidden'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>



      </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <!--    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"-->

      <ResponsiveNavSlideOutMenu />

  </div>
</template>

<script setup>
import { router, Link, usePage } from '@inertiajs/vue3'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"
import JetApplicationMark from "@/Jetstream/ApplicationMark"
import NotificationButton from "@/Components/Global/Notifications/NotificationButton"
import ResponsiveNavSlideOutMenu from '@/Components/Global/Navigation/ResponsiveNavSlideOutMenu.vue'

// const page = usePage();

// const showingNavigationDropdown = ref(false)

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()


let props = defineProps({
  user: Object,
})


//
// const checkOverflow = () => {
//     if (scrollableDiv.value) {
//         isContentOverflowing.value = scrollableDiv.value.scrollHeight > scrollableDiv.value.clientHeight;
//     }
// };










// function loadStreamPage() {
//     videoPlayerStore.makeVideoFullPage()
//     appSettingStore.ott = 0
//     userStore.showNavDropdown = false
// }




function navigateToStream() {
  videoPlayerStore.makeVideoFullPage()
  appSettingStore.ott = 0
  userStore.closeNavDropdown()
  if (!videoPlayerStore.currentPageIsStream) {
    userStore.prevUrl = window.history.state.url
  }
  router.visit(`/stream`)
}



</script>

<style scoped>



</style>
