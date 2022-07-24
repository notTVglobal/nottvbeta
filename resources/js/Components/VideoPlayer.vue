<template>
<!--    style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"-->
    <div class="absolute mt-16 top-0 right-0 h-max z-10">
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
            <video controls autoplay muted loop ref="VideoPlayer" :class="videoClass">
                <source :src="videoId" type="video/webm"/>

                Sorry, your browser doesn't support embedded videos.
            </video>
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

</template>

<script setup>
import { ref, inject } from "vue";
import { isClient } from "@vueuse/shared";
import { useDraggable } from "@vueuse/core";
import { UseDraggable as Draggable } from "@vueuse/components";

let videoClass = inject('videoClass');

let videoId = ref([`../../images/Spring-BlenderOpenMovie-WhWc3b3KhnY.webm`]);

// Use the prop.video to send/receive videoPlayer data from the server
defineProps({
    video: Object
});


// const el = ref<HTMLElement | null>(null)
// const innerWidth = isClient ? window.innerWidth : 200
// const { x, y, style } = useDraggable(el, {
//     initialValue: { x: innerWidth / 4.2, y: 80 },
//     preventDefault: true,
// })







</script>
