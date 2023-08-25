<template>
    <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black last:mr-0 mr-1">
        <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /> {{ videoPlayerStore.viewerCount }}
    </div>

</template>

<script setup>
import {onBeforeMount, onBeforeUnmount, ref} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";

let videoPlayerStore = useVideoPlayerStore()

onBeforeMount(async() => {
    await connect();
});

function connect() {
    console.log('GET VIEWER COUNT');
    getViewerCount();
}

function getViewerCount() {
    axios.get('/api/getCurrentViewers')
        .then(response => {
            videoPlayerStore.viewerCount = response;
        })
        .catch(error => {
            console.log(error);
        })
}

function disconnect() {
    window.Echo.leave("channel." + chatStore.currentChannel.id );
    console.log('CHANNEL DISCONNECTED');
}

onBeforeUnmount(() => {
    videoPlayerStore.viewerCount = null
    disconnect();
});

const channel = Echo.private('channel.' + videoPlayerStore.currentChannelId)
channel.subscribed(() => {
}).listen('channel.' + videoPlayerStore.currentChannelId, (event) => {
    if (event.channel_id === videoPlayerStore.currentChannelId) {
        videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + event.viewerCount;
    }
})

</script>
