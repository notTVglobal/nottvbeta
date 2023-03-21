<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >


        <div v-if="videoPlayerStore.showPlaylist" class="playlistForStreamPageStandard w-96 max-w-[12rem] bottom-0 bg-opacity-80 bg-orange-600 text-sm text-white pb-12
                chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">

<!--        Previous version (full screen background for Ott) -->
<!--        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard max-w-screen h-screen left-0 bottom-0 sm:bottom-8 bg-opacity-80 bg-green-600 text-sm text-white pb-12-->
<!--            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">-->

            <div>
                <div class="flex flex-col p-5 mt-10">
                    <div class="text-3xl text-center font-semibold uppercase mb-3 w-full bg-orange-900 text-white p-2">PLAYLIST</div>
                </div>

                <div class="absolute">
                    <div class="relative h-[calc(h-100%-16rem)] top-0 pr-5 space-y-2">
                        <div>
                            Add a loop here to display the playlist... scrollable. If the current channel is the users_channel,
                            display the playlist for the user. If the current channel is another channel (e.g., stream), display
                            the stream_playlist.
                        </div>
                    </div>

                </div>
            </div>






            <button v-touch="()=>videoPlayerStore.togglePlaylist()"
                    v-if="videoPlayerStore.showPlaylist" class="fixed bottom-5 right-32 opacity-100 w-15 h-15 p-4 rounded-full bg-orange-800
                    text-gray-50 hover:bg-blue-800 hover:text-blue-200 grid justify-center content-center
                    cursor-pointer font-semibold text-xs">
                CLOSE PLAYLIST
            </button>
        </div>

    </Transition>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";

let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})



</script>
