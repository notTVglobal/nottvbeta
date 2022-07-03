<template>
        <video controls autoplay ref="VideoPlayer">
            <source :src="`images/Spring-BlenderOpenMovie-WhWc3b3KhnY.webm`" type="video/webm" />
            Sorry, your browser doesn't support embedded videos.
        </video>

</template>

<script setup>
import { ref } from 'vue'

defineProps({
    VideoId: Object
});

const VideoId = ref([`images/Spring-BlenderOpenMovie-WhWc3b3KhnY.webm`])

</script>
