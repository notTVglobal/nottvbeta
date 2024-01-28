<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >
<!--    <div>-->

<!--      <upgrade v-if="shouldRenderUpgrade" />-->
<!--      <div v-if="shouldRenderContent">-->
        <!-- Your content here -->

    <div v-if="shouldDisplayOtt"
         :class="[appSettingStore.fullPage ? 'channelsFullPageContainer' : 'ottTopRightDisplay', 'bg-green-900 hide-scrollbar']">
      <div class="channelsMenu ">

        <div v-if="!showUpgrade">
          <upgrade/>
        </div>


        <div v-else ref="scrollRef"
             class="bg-green-900 overflow-y-auto scrollbar-custom">

<!--          <div v-else ref="scrollRef" class="channelsMenu ottTopRightDisplay bg-green-900 overflow-y-auto scrollbar-custom">-->

<!--            <div v-if="appSettingStore.fullPage" class="flex flex-col p-5 mt-2 mb-3 ">-->
<!--              <div class="text-3xl text-center font-semibold uppercase w-full bg-yellow-600 text-black p-2">FILTERS</div>-->
<!--            </div>-->

<!--            <div v-else class="text-xs font-semibold uppercase mb-3 w-full bg-yellow-600 text-black p-2">FILTERS</div>-->


            <div v-if="appSettingStore.fullPage" class="flex flex-col p-5 mt-2 mb-3">
              <div class="text-3xl text-center font-semibold uppercase w-full bg-green-900 text-white p-2">
                CHANNELS
              </div>
            </div>

          <div v-else class="text-xs font-semibold uppercase mt-2 mb-3 w-full bg-green-800 text-white px-2 py-2 text-center">
            CHANNELS
          </div>

          <div class="pb-24 w-full scrollbar-hide"
               :class="[{'h-[calc(100vh-18rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
            <ChannelsList />
          </div>

          <!--          <div-->
          <!--              :class="[{'w-full':appSettingStore.ott === 2},{'px-5 space-y-1 overflow-y-scroll hide-scrollbar':videoPlayerStore.ottChannels}]">-->
          <!--            <Channels/>-->
          <!--          </div>-->
          <div v-if="appSettingStore.fullPage" class="closeButtonContainer">
            <button v-touch="()=>appSettingStore.toggleOttChannels()"
                    v-if="appSettingStore.ott === 2" class="channelsCloseButton">
              CLOSE CHANNELS
            </button>
          </div>

          <div class="fixed w-full bottom-2 text-center">
            <ScrollDownIndicator/>
          </div>

        </div>
      </div>

<!--      <upgrade-->
<!--          v-if="(appSettingStore.ott === 2 || videoPlayerStore.ottChannels) && !userStore.isSubscriber && !userStore.isVip && !userStore.isAdmin"/>-->
<!--      <div-->
<!--          v-if="(appSettingStore.ott === 2 || videoPlayerStore.ottChannels) && (userStore.isSubscriber || userStore.isVip || userStore.isAdmin)">-->


    </div>
  </Transition>
</template>

<script setup>
import { computed, provide, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
// import { useStreamStore } from "@/Stores/StreamStore"
// import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import ChannelsList from "@/Components/Global/Channels/ChannelsList"
import Upgrade from "@/Components/Global/Ott/Elements/Upgrade"
import ScrollDownIndicator from "@/Components/Global/UserHints/ScrollDownIndicator"

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
// const streamStore = useStreamStore()
// const chat = useChatStore()
const userStore = useUserStore()

const scrollRef = ref(null)
provide('scrollRef', scrollRef)

let props = defineProps({
  user: Object,
})

const shouldDisplayOtt = computed(() => {
  return appSettingStore.ott === 2
})
const showUpgrade = computed(() => {
  return appSettingStore.ott === 2 &&
      (!props.user.isSubscriber ||
          !props.user.isVip ||
          !props.user.isAdmin);
});


// Create reactive properties to hold the conditions
// const shouldRenderUpgrade = computed(() => {
//   return (
//       (appSettingStore.ott === 2 || videoPlayerStore.ottChannels) &&
//       !userStore.isSubscriber &&
//       !userStore.isVip &&
//       !userStore.isAdmin
//   );
// });
//
// const shouldRenderContent = computed(() => {
//   return (
//       (appSettingStore.ott === 2 || videoPlayerStore.ottChannels) &&
//       (userStore.isSubscriber || userStore.isVip || userStore.isAdmin)
//   );
// });


// let playVideo = (source) => {
//   videoPlayerStore.loadNewSourceFromMist(source)
// }
//
// const ottDisplayShow = computed(() => ({
//   'hidden': !videoPlayerStore.ott
// }))
//
// const ottDisplay = computed(() => ({
//   ottDisplayMobile: userStore.isMobile,
//   ottDisplayDesktop: !userStore.isMobile
// }))

</script>

<style scoped>


</style>
