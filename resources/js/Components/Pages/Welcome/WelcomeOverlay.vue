<template>
  <Transition
      enter-from-class="opacity-0 scale-125"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-125"
  >
    <!--        <div v-if="welcomeStore.showOverlay===true">-->
    <div class="">
      <div class="grid md:grid-cols-1 grid-cols-1 align-items-center -mt-32">
        <img :src="`/storage/images/logo_white.svg`" class="block w-96 p-10" alt="notTV Logo">
        <div class="my-4 text-center">
          <button
              class="text-2xl font-semibold bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded disabled:bg-gray-400"
              @click="$emit('watchNow')"
          >
            <font-awesome-icon icon="fa-solid fa-play"/>
            Watch Now
          </button>


        </div>
        <div class="my-2 text-center">
          OR
        </div>
        <!--                            <div class="text-center font-semibold text-2xl px-5 py-3">Returning Soon!</div>-->
        <div class="text-center font-semibold text-2xl px-5 py-3">
          SCROLL DOWN
        </div>
        <!--                                                    <div class="my-2 text-center">OR</div>-->
        <!--                        <div class="text-center font-semibold text-2xl px-5 py-3">LOG IN</div>-->


      </div>
    </div>
    <!--                </div>-->

  </Transition>

</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import JetApplicationLogo from '@/Jetstream/ApplicationLogo'

const welcomeStore = useWelcomeStore()
const videoPlayerStore = useVideoPlayerStore()

defineProps({
  show: Boolean
});

</script>
