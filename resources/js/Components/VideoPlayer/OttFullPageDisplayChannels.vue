<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >


        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard w-96 max-w-[12rem] bottom-0 bg-opacity-80 bg-green-600 text-sm text-white pb-12
                chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">

<!--        Previous version (full screen background for Ott) -->
<!--        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard max-w-screen h-screen left-0 bottom-0 sm:bottom-8 bg-opacity-80 bg-green-600 text-sm text-white pb-12-->
<!--            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">-->


                <div class="flex flex-col p-5 mt-10">
                    <div class="text-3xl text-center font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">CHANNELS</div>
                </div>
                <div class="absolute">
                    <div class="relative h-[calc(h-100%-16rem)] top-0 pr-5 space-y-2 pb-24">

                      <Channels/>

                    </div>

                </div>

            <button v-touch="()=>videoPlayerStore.toggleChannels()"
                    v-if="videoPlayerStore.showChannels" class="fixed bottom-5 right-32 opacity-100 w-15 h-15 p-4 rounded-full
                    text-gray-50 bg-green-900 hover:bg-green-800 grid justify-center content-center
                    cursor-pointer font-semibold text-xs">
                CLOSE CHANNELS
            </button>
        </div>

    </Transition>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore"
import Channels from "@/Components/VideoPlayer/Channels/Channels"

let videoPlayerStore = useVideoPlayerStore();

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


