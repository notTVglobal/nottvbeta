<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >


            <div v-if="videoPlayerStore.ottChannels" class="channelsFullPageContainer">

                <div class="flex flex-col p-5 mt-2">
                    <div class="text-3xl text-center font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">CHANNELS</div>
                </div>

                <div class="px-5 space-y-1 overflow-y-scroll scrollbar-hide">
                  <Channels/>
                </div>

                <button v-touch="()=>videoPlayerStore.toggleChannels()"
                        v-if="videoPlayerStore.ottChannels" class="channelsCloseButton">
                    CLOSE CHANNELS
                </button>
        </div>

    </Transition>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore"
import Channels from "@/Components/VideoPlayer/Channels/Channels"

let videoPlayerStore = useVideoPlayerStore()

let props = defineProps({
    user: Object,
    // channelClasses: 'w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md disabled:disabledChannel disabled:text-gray-300 disabled:cursor-not-allowed',
})

// function changeChannel(name) {
//     videoPlayerStore.disconnectViewerFromChannel()
//     window.Echo.leaveChannel('viewerCount.' + videoPlayerStore.currentChannelId)
//     videoPlayerStore.changeChannel(name)
//     window.Echo.channel('viewerCount.' + videoPlayerStore.currentChannelId)
// }

// onBeforeMount(async() => {
//     await connect();
// });
//
// function connect() {
//     console.log('GET VIEWER COUNT');
//     getViewerCount();
// }
//
// function getViewerCount() {
//     axios.post('/api/getCurrentViewers', {'channel_id': videoPlayerStore.currentChannelId})
//         .then(response => {
//             videoPlayerStore.viewerCount = response.data[0];
//         })
//         .catch(error => {
//             console.log(error);
//         })
// }

// const channel = Echo.private('channel.' + videoPlayerStore.currentChannelId)
// channel.subscribed(() => {
// }).listen('channel.' + videoPlayerStore.currentChannelId, (event) => {
//     if (event.channel_id === videoPlayerStore.currentChannelId) {
//         videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + event.viewerCount;
//     }
// })


</script>


