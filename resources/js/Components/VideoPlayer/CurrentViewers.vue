<template>
    <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black last:mr-0 mr-1">
        <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /> {{props.currentViewers}}<span v-text="videoPlayerStore.viewerCount" :key="videoPlayerStore.viewerCount"/>
    </div>

</template>

<script setup>
import { onBeforeUnmount, onUpdated, onMounted, computed, ref, reactive } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useUserStore } from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

let props = defineProps({
    user: Object,
    currentViewers: Object
})

// Echo.private(`channel.${videoPlayerStore.currentChannelId}`)
//     .listen('ViewerCountIncrement', (event) => {
//         videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + 1;
//     })
//     .listen('ViewerCountDecrement', (event) => {
//         videoPlayerStore.viewerCount = videoPlayerStore.viewerCount - 1;
//     })



onUpdated(() => {
    Echo.channel('viewerCount')
        .listen('ViewerCountIncrement', (event) => {
        if (event.data.channel_id === videoPlayerStore.currentChannelId) {
            videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + 1;}
    }).listen('ViewerCountDecrement', (event) => {
        if (event.data.channel_id === videoPlayerStore.currentChannelId) {
            videoPlayerStore.viewerCount = videoPlayerStore.viewerCount - 1;}
    })
})

onBeforeUnmount(() => {
    // Echo.private(`channel.${videoPlayerStore.currentChannelId}`)
    Echo.channel('viewerCount')
        .stopListening('ViewerCountIncrement')
        .stopListening('ViewerCountDecrement')
})


</script>
