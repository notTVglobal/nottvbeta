<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >

    <!--    <div v-if="appSettingStore.ott === 5 || videoPlayerStore.ottFilters"-->
    <!--         :class="[{'filtersFullPageContainer':videoPlayerStore.ottFilters},{'ottTopRightDisplay':appSettingStore.ott === 5}]"-->
    <!--         class="bg-yellow-500 hide-scrollbar">-->


    <div v-if="shouldDisplayOtt"
         :class="[appSettingStore.fullPage ? 'filtersFullPageContainer opacity-95' : 'ottTopRightDisplay', 'bg-yellow-500 hide-scrollbar']">
      <div class="h-full w-full overflow-y-scroll scrollbar-hide">

        <div v-if="!showUpgrade">
          <upgrade/>
        </div>

        <div v-else class="now-playing w-full h-full bg-yellow-500 text-black p-4 overflow-y-hidden scrollbar-hide">

          <div v-if="appSettingStore.fullPage" class="flex flex-col p-5 mt-2 mb-3 ">
            <div class="text-3xl text-center font-semibold uppercase w-full bg-yellow-600 text-black p-2">FILTERS</div>
          </div>

          <div v-else class="text-xs font-semibold uppercase mb-3 w-full bg-yellow-600 text-black p-2">FILTERS</div>

          <div class="pb-24 w-full overflow-y-hidden scrollbar-hide"
               :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
            Coming Soon!
          </div>

<!--          <div class="top-0 px-5 space-y-2">-->
<!--            <div class="text-black">-->
<!--              Coming Soon!-->
<!--            </div>-->
<!--          </div>-->

        </div>

      </div>

      <div v-if="appSettingStore.fullPage" class="closeButtonContainer">
        <button v-touch="()=>appSettingStore.closeOtt()"
                v-if="appSettingStore.ott === 5" class="filtersCloseButton">
          CLOSE FILTERS
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import Upgrade from '@/Components/Global/Ott/Elements/Upgrade.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let props = defineProps({
  user: Object,
})

const shouldDisplayOtt = computed(() => {
  return appSettingStore.ott === 5
})
const showUpgrade = computed(() => {
  return appSettingStore.ott === 5 &&
      (!props.user.isSubscriber ||
          !props.user.isVip ||
          !props.user.isAdmin);
});


// let playVideo = (source) => {
//   videoPlayerStore.loadNewSourceFromMist(source)
// }

// const ottDisplayShow = computed(() => ({
//   'hidden': !videoPlayerStore.ott
// }))

// const ottChannels = computed(() => ({
//   channelsOttMobile: userStore.isMobile,
//   channelsOttDesktop: !userStore.isMobile
// }))



</script>

<style scoped>


</style>
