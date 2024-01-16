<template>
  <div class="">

    <div v-if="!channelStore.channelsLoaded"
         :key="channelStore.channelsLoaded"
         class="w-full h-full m-auto text-center align-middle">
      <span class="loading loading-spinner text-accent"></span>
    </div>
    <div v-else>
      <div v-for="channel in channelStore.channel_list"
           :key="channel.id"
           class="channel">

        <button :class="[ channelClass, {activeChannelFullPage:channelStore.currentChannelId===channel.id }]"
                @click="changeChannel(channel)">
          {{ channel.name }}
        </button>
      </div>

      <ChannelFooter/>
    </div>

    <!--        <div class="fixed w-full bottom-4 text-center fade-out">-->
    <!--            <ScrollDownIndicator scrollRef="scrollRef"/>-->
    <!--        </div>-->


  </div>

</template>

<script setup>
import { computed } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChannelStore } from "@/Stores/ChannelStore"
import ChannelFooter from "@/Components/Global/VideoPlayer/Channels/ChannelFooter"

const videoPlayerStore = useVideoPlayerStore()
let channelStore = useChannelStore()
const userStore = useChannelStore()

let props = defineProps({
  channelClasses: String,
})

channelStore.getChannels()

async function changeChannel(channel) {
  await channelStore.disconnectViewerFromChannel()
  await channelStore.changeChannel(channel)
  videoPlayerStore.toggleChannels()
  videoPlayerStore.toggleOttChannels()
  appSettingStore.ott = 0
  Inertia.reload()
}

const channelClass = computed(() => ({
  channelsFullPage: appSettingStore.fullPage,
  channelsTopRight: !appSettingStore.fullPage,
}))

</script>
