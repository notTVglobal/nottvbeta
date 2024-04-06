<template>
  <!--    <Transition-->
  <!--        enter-from-class="opacity-0"-->
  <!--        enter-to-class="opacity-100"-->
  <!--        enter-active-class="transition duration-300"-->
  <!--        leave-active-class="transition duration-200"-->
  <!--        leave-from-class="opacity-100"-->
  <!--        leave-to-class="opacity-0"-->
  <!--    >-->

  <div class="flex justify-center">

    <!-- Video TopRight Controls -->
    <div v-if="!appSettingStore.fullPage" class="fixed flex justify-center right-1 top-60 px-2 w-96 z-50">


      <VideoControlsButtons/>
      <!--                        <Link v-touch="()=>(route('stream'))" @click="videoPlayerStore.makeVideoFullPage()" :href="route('stream')" :active="route().current('stream')">-->
      <!--                        <button class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600">-->
      <!--                            BIG</button></Link>-->


      <!--                        <div class="icon-container">-->
      <!--                            <button v-touch="()=>(route('stream'))" @click="goToStreamPage">-->
      <!--                                <svg class="fill-current icon"-->

      <!--                                     xmlns="http://www.w3.org/2000/svg"-->
      <!--                                     width="48"-->
      <!--                                     height="48"-->
      <!--                                     viewBox="0 0 24 24">-->
      <!--                                    <path d="M3,3H21V21H3V3M5,5V19H19V5H5Z" />-->
      <!--                                    <g transform="scale(0.5) translate(12, 12)">-->
      <!--                                        <path-->
      <!--                                            d="M13.8995 4.10052V2.10052H21.8995V10.1005H19.8995V5.51477L14.1213 11.293L12.7071 9.87878L18.4854 4.10052H13.8995Z"-->
      <!--                                        />-->
      <!--                                        <path-->
      <!--                                            d="M4.10046 13.8995H2.10046V21.8995H10.1005V19.8995H5.51468L11.2929 14.1212L9.87872 12.707L4.10046 18.4853V13.8995Z"-->
      <!--                                        />-->
      <!--                                    </g>-->
      <!--                                </svg>-->
      <!--                            </button>-->
      <!--                        </div>-->


      <!--                        <button v-if="videoPlayerStore.muted"-->
      <!--                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"-->
      <!--                                @click="videoPlayerStore.unmute()">-->
      <!--                            UNMUTE</button>-->

      <!--                        <button v-if="!videoPlayerStore.muted"-->
      <!--                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"-->
      <!--                                @click="videoPlayerStore.mute()">-->
      <!--                            MUTE</button>-->

      <!--                        <button-->
      <!--                            class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"-->
      <!--                            @click="videoPlayerStore.back()"-->
      <!--                            disabled>-->
      <!--                            PREV</button>-->

      <!--                        <button v-if="!videoPlayerStore.paused"-->
      <!--                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"-->
      <!--                                @click="videoPlayerStore.pause()">-->
      <!--                            PAUSE</button>-->

      <!--                        <button v-if="videoPlayerStore.paused"-->
      <!--                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"-->
      <!--                                @click="videoPlayerStore.play()">-->
      <!--                            PLAY</button>-->

      <!--                        <button-->
      <!--                            class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"-->
      <!--                            @click="videoPlayerStore.next()"-->
      <!--                            disabled >-->
      <!--                            NEXT</button>-->


    </div>


  </div>

  <!--    </Transition>-->
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import VideoControlsButtons from "@/Components/Global/VideoPlayer/VideoControls/Elements/VideoControlsButtons"

const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

defineProps({
  show: Boolean
});

const goToStreamPage = () => {
  videoPlayerStore.makeVideoFullPage()
  Inertia.visit('stream')
}


</script>

<style scoped>

.sr-only {
  position: absolute;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  width: 1px;
  height: 1px;
  margin: -1px;
  padding: 0;
  border: 0;
}

/* Normal state styles for the icon */
.icon-container {
  display: inline-block;
}

.icon {
  fill: currentColor; /* Use the current text color as the icon color */
  transition: fill 0.3s ease; /* Smooth color transition */
}

/* Hover state styles for the icon */
.icon-container:hover .icon {
  fill: #f59e0b; /* Change to text-yellow-500 color on hover */
}


</style>
