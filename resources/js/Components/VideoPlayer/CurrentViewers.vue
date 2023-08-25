<template>
    <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black last:mr-0 mr-1">
        <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /> <span v-text="viewerCount" />
    </div>

</template>

<script setup>
import { computed } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";

let videoPlayerStore = useVideoPlayerStore()

const viewerCount = computed(() => videoPlayerStore.viewerCount)

// const channel = Echo.private('channel.' + '1')
// channel.subscribed(() => {
// }).listen('channel.' + '1', (event) => {
//     videoPlayerStore.viewerCount + event
// })

function connectToViewerCount() {
    console.log('channel connected 2')
    const channel = Echo.private('channel.' + videoPlayerStore.currentChannelId)
    channel.subscribed(() => {
    }).listen('channel.' + videoPlayerStore.currentChannelId, (event) => {
        computed(() => videoPlayerStore.viewerCount + event)
        // videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + event;
        // if (event.channel_id === videoPlayerStore.currentChannelId) {
        //     videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + event.viewerCount;
        // }
        // console.log('channel connected4')
        // console.log(event);
    })
}
connectToViewerCount()
console.log('channel connected 3')


</script>
