<template>
    <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black last:mr-0 mr-1">
        <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /><span v-text="channelStore.viewerCount"/>
    </div>

</template>

<script setup>
import {onBeforeUnmount, onUpdated, onMounted, computed, ref, reactive, onBeforeUpdate, onBeforeMount} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useUserStore } from "@/Stores/UserStore";
import { useChannelStore } from "@/Stores/ChannelStore"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let channelStore = useChannelStore()

let props = defineProps({
    user: Object,
    currentViewers: ref()
})
onMounted(() => {

})

// Echo.channel('viewerCount')
//     .listen('ViewerCountIncrement', (event) => {
//         userStore.updateStore()
//         console.log('test count up')
//         // videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + 1;
//     })
//     .listen('ViewerCountDecrement', (event) => {
//         userStore.substractStore()
//         console.log('test count down')
//         // videoPlayerStore.viewerCount = videoPlayerStore.viewerCount - 1;
//     })

// window.Echo.channel('viewerCount.' + channelStore.currentChannelId)
//     .listen('ViewerJoinChannel', (e) => {
//         console.log(e);
//     })
//     .listen('ViewerLeaveChannel', (e) => {
//         console.log(e);
//     })




// onBeforeMount(() => {
//     Echo.channel('viewerCount')
//         .listen('ViewerCountIncrement', (event) => {
//         if (event.data.channel_id === videoPlayerStore.currentChannelId) {
//             videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + 1;}
//     }).listen('ViewerCountDecrement', (event) => {
//         if (event.data.channel_id === videoPlayerStore.currentChannelId) {
//             videoPlayerStore.viewerCount = videoPlayerStore.viewerCount - 1;}
//     })
// })

onBeforeUnmount(() => {
    window.Echo.leaveChannel(`viewerCount.${channelStore.currentChannelId}`)
})


</script>
