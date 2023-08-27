<template>
    <div>
<!--        <div v-for="channel in props.channels"-->
<!--             :key="channel.id"-->
<!--             class="">-->
<!--            <div>{{channel.name}}</div>-->



            <div v-for="channel in channelStore.channel_list"
                 :key="channel.id"
                 class="show">

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

// let props = defineProps({
//     channelClasses: String,
// })

channelStore.getChannels()

function changeChannel(channel) {
    channelStore.disconnectViewerFromChannel()
    window.Echo.leaveChannel('viewerCount.' + channelStore.currentChannelId)
    channelStore.changeChannel(channel)
    window.Echo.channel('viewerCount.' + channelStore.currentChannelId)
        .listen('ViewerJoinChannel', (e) => {
            console.log('test count up')
            channelStore.viewerCount = channelStore.viewerCount + 1
            // channelStore.viewerIncrement()
        })
        .listen('ViewerLeaveChannel', (e) => {
            console.log('test count down')
            channelStore.viewerCount = channelStore.viewerCount - 1
            // channelStore.viewerDecrement()
        })
    videoPlayerStore.toggleChannels()
    videoPlayerStore.toggleOttChannels()
    videoPlayerStore.ott = 0
}

const channelClass = computed(() => ({
    channelsFullPage: videoPlayerStore.fullPage,
    channelsTopRight: !videoPlayerStore.fullPage,
}))

</script>
