<template>
<!--  <div class="max-w-[12rem]">-->
  <div class="w-100">
<!--    <div class="max-w-[12rem]">-->
    <div class="w-100">
      <video-player :id="`aux-player`" :options="videoOptions"/>
    </div>
  </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()

const videoPlayerStore = useVideoPlayerStore()

let playerName = 'aux-player'

// let props = defineProps({
//     id: String,
//     src: String,
//     type: String,
// })

</script>


<script>
import VideoPlayer from '@/Components/Global/VideoPlayer/VideoJs/VideoJsAux'

let videoSource = 'https://mist.nottv.io/hls/test/index.m3u8'
let videoSourceType = 'application/x-mpegURL'

export default {
  name: 'VideoPlayer',
  components: {
    VideoPlayer
  },
  data() {
    return {
      videoOptions: {
        autoplay: true,
        muted: true,
        controls: true,
        fill: true,
        sources: [
          {
            src: videoSource,
            type: videoSourceType,
          }
        ]
      }
    };
  },
  props: {
    id: String,
    src: String,
    type: String,
  },
  setup(props) {

  }
};


</script>
