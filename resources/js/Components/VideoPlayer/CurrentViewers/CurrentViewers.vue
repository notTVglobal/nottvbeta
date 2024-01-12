<template>
    <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-black last:mr-0 mr-1">
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

</script>
