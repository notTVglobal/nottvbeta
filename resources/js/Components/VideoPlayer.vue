<template>
<!--    style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"-->

<!--        <Draggable-->
<!--            v-slot="{ x, y }"-->
<!--            p="x-4 y-2"-->
<!--            border="~ gray-400 rounded"-->
<!--            shadow="~ hover:lg"-->
<!--            class="fixed bg-$vt-c-bg select-none cursor-move z-10"-->
<!--            :initial-value="{ x: innerWidth / 3.9, y: 150 }"-->
<!--            :prevent-default="true"-->
<!--            storage-key="vueuse-draggable-pos"-->
<!--            storage-type="session"-->
<!--        >-->
<div class="videoContainer">
    <div :class="videoPlayer.class">
        <div class="absolute top-16 left-0 p-5 drop-shadow" v-if="videoPlayer.fullPage">Now Playing: {{ videoPlayer.videoName }}</div>
            <video controls autoplay muted loop id="videoPlayer" class="object-contain w-full">
                <source id="src" :src="videoFirstPlay" type="video/webm"/>
                Sorry, your browser doesn't support embedded videos.
            </video>
        <button @click="loadVideo1" class="bg-gray-300 text-black p-1 m-2">Spring</button>
        <button @click="loadVideo2" class="bg-gray-300 text-black p-1 m-2">Dune</button>
        <button @click="loadVideo3" class="bg-gray-300 text-black p-1 m-2">1984</button>
        <button @click="loadVideo4" class="bg-gray-300 text-black p-1 m-2">The Terminator</button>
        <button @click="loadVideo5" class="bg-gray-300 text-black p-1 m-2">Natural World</button>
        <div v-if="!videoPlayer.fullPage"> Now Playing: {{ videoPlayer.videoName }} </div>
            <!--            <iframe src="https://iframe.videodelivery.net/39ce0cc05aaf8186079fb844942f0afe"-->
            <!--                    class="w-96"-->
            <!--                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"-->
            <!--                    allowfullscreen="true">-->
            <!--            </iframe>-->
<!--            <div class="text-xs opacity-50 bg-gray-800 text-gray-200">-->
<!--                <p>({{ Math.round(x) }}, {{ Math.round(y) }}) CLICK HERE TO MOVE VIDEO PLAYER</p>-->
<!--                <p>This video is a webm format and won't playback on an iPhone or iPad.<br />-->
<!--                    This is just for testing purposes.-->
<!--                </p>-->
<!--            </div>-->
<!--        </Draggable>-->
    </div>
</div>
</template>

<script setup>
import { ref } from "vue";
// import { isClient } from "@vueuse/shared";
// import { useDraggable } from "@vueuse/core";
// import { UseDraggable as Draggable } from "@vueuse/components";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";

let videoPlayer = useVideoPlayerStore();
let videoFirstPlay = ref([`../../images/Spring-BlenderOpenMovie-WhWc3b3KhnY.webm`]);
videoPlayer.videoName = "Spring";

function loadVideo1() {
    videoPlayer.loadVideo1()
    document.getElementById("src").src = videoPlayer.videoSourceId;
    document.getElementById("videoPlayer").load();
}
function loadVideo2() {
    videoPlayer.loadVideo2()
    document.getElementById("src").src = videoPlayer.videoSourceId;
    document.getElementById("videoPlayer").load();
}
function loadVideo3() {
    videoPlayer.loadVideo3()
    document.getElementById("src").src = videoPlayer.videoSourceId;
    document.getElementById("videoPlayer").load();
}
function loadVideo4() {
    videoPlayer.loadVideo4()
    document.getElementById("src").src = videoPlayer.videoSourceId;
    document.getElementById("videoPlayer").load();
}
function loadVideo5() {
    videoPlayer.loadVideo5()
    document.getElementById("src").src = videoPlayer.videoSourceId;
    document.getElementById("videoPlayer").load();
}

// const el = ref<HTMLElement | null>(null)
// const innerWidth = isClient ? window.innerWidth : 200
// const { x, y, style } = useDraggable(el, {
//     initialValue: { x: innerWidth / 4.2, y: 80 },
//     preventDefault: true,
// })

</script>
