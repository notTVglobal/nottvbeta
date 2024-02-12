<template>
  <div class="h-full">

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

            <div class="fixed w-full bottom-4 text-center fade-out">
                <ScrollDownIndicator scrollRef="scrollRef"/>
            </div>


  </div>

</template>

<script setup>
import { computed, onMounted, provide, ref } from 'vue'
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChannelStore } from "@/Stores/ChannelStore"
import ChannelFooter from "@/Components/Global/Channels/ChannelFooter"
import ScrollDownIndicator from '@/Components/Global/UserHints/ScrollDownIndicator.vue'

const videoPlayerStore = useVideoPlayerStore()
let channelStore = useChannelStore()
const userStore = useChannelStore()

let props = defineProps({
  channelClasses: String,
})

const scrollRef = ref(null)
provide('scrollRef', scrollRef)

onMounted(async () => {
  await videoPlayerStore.getMistServerUri()
})

channelStore.reloadChannels()

async function changeChannel(channel) {
  // await channelStore.disconnectViewerFromChannel() // This is in the channelStore.changeChannel function.
  await channelStore.changeChannel(channel)
  // videoPlayerStore.toggleChannels()
  // videoPlayerStore.toggleOttChannels()
  // appSettingStore.ott = 0
  // Inertia.reload()
}

const channelClass = computed(() => ({
  channelsFullPage: appSettingStore.fullPage,
  channelsTopRight: !appSettingStore.fullPage,
}))

</script>
