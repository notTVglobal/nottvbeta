<template>
    <div>
<!--        <div v-for="channel in props.channels"-->
<!--             :key="channel.id"-->
<!--             class="">-->
<!--            <div>{{channel.name}}</div>-->

        <div v-for="channel in channelStore.channel_list"
             :key="channel.id"
             class="show mt-8 ">

            <button :class="[ channelClasses, {activeChannelFullPage:videoPlayerStore.currentChannelName==='one' }]"
                    @click="changeChannel(channel)">
                {{ channel.name }}</button>
        </div>

        <button :class="[ channelClasses, {activeChannelFullPage:videoPlayerStore.currentChannelName==='one' }]"
                @click="changeChannel('one')">
            LIVE NOW</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='ambient' }"
                @click="changeChannel('ambient')">
            AMBIENT</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='news' }"
                @click="changeChannel('news')">
            NEWS</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='talk' }"
                @click="changeChannel('talk')">
            TALK</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='documentary' }"
                @click="changeChannel('documentary')">
            DOCUMENTARY</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='music' }"
                @click="changeChannel('music')">
            MUSIC</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='drama' }"
                @click="changeChannel('drama')">
            DRAMA</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='comedy' }"
                @click="changeChannel('comedy')">
            COMEDY</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='education' }"
                @click="changeChannel('education')">
            EDUCATION</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='spirituality' }"
                @click="changeChannel('spirituality')">
            SPIRITUALITY</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                disabled
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='reality' }"
                @click="changeChannel('reality')">
            REALITY</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                disabled
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='variety' }"
                @click="changeChannel('variety')">
            VARIETY</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                disabled
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='sports' }"
                @click="changeChannel('sports')">
            SPORTS</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                disabled
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='kids' }"
                @click="changeChannel('kids')">
            KIDS</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='local' }"
                @click="changeChannel('local')">
            LOCAL</button>
        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                disabled
                :class="{ activeChannel:videoPlayerStore.currentChannelName==='world' }"
                @click="changeChannel('world')">
            WORLD</button>

        <div class="px-4 pt-2 pb-24 bg-green-700 border-b border-0.2 border-green-800 shadow-md space-y-2">
            <div class="">Channel One is our premiere promotional channel featuring handpicked content and creators from the notTV network.</div>
        </div>
</div>

</template>

<script setup>
import {onBeforeMount, ref} from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChannelStore } from "@/Stores/ChannelStore";

let videoPlayerStore = useVideoPlayerStore();
let channelStore = useChannelStore();

let props = defineProps({
    channelClasses: String,
})

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
    // emit close channels display on fullPage
}

</script>
