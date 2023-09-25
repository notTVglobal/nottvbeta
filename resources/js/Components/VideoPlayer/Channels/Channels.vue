<template>
    <div class="overflow-y-scroll scrollbar-hide">

        <div v-if="!channelStore.channelsLoaded"
             :key="channelStore.channelsLoaded"
             class="w-full h-full m-auto text-center align-middle">
            <span class="loading loading-spinner text-accent"></span>
        </div>

            <div v-for="channel in channelStore.channel_list"
                 :key="channel.id"
                 class="channel">

                <button :class="[ channelClass, {activeChannelFullPage:channelStore.currentChannelId===channel.id }]"
                        @click="changeChannel(channel)">
                    {{ channel.name }}</button>
            </div>

            <ChannelFooter />

    </div>

</template>

<script setup>
import { onBeforeMount, ref, computed } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChannelStore } from "@/Stores/ChannelStore";
import { useUserStore } from "@/Stores/UserStore";
import ChannelFooter from "@/Components/VideoPlayer/Channels/ChannelFooter"

let videoPlayerStore = useVideoPlayerStore();
let channelStore = useChannelStore();
let userStore = useChannelStore();

let props = defineProps({
    channelClasses: String,
})

channelStore.getChannels()

async function changeChannel(channel) {
    await channelStore.disconnectViewerFromChannel()
    await channelStore.changeChannel(channel)
    videoPlayerStore.toggleChannels()
    videoPlayerStore.toggleOttChannels()
    videoPlayerStore.ott = 0
}

const channelClass = computed(() => ({
    channelsFullPage: videoPlayerStore.fullPage,
    channelsTopRight: !videoPlayerStore.fullPage,
}))

</script>
