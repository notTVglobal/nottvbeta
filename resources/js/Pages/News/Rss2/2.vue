<template>
<div>
    <div id="topDiv">
        <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

    </div>
</div>

</template>

<script setup>

import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";
import { useUserStore } from "@/Stores/UserStore";
import {onMounted} from "vue";
import Message from "@/Components/Modals/Messages.vue";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

</script>
