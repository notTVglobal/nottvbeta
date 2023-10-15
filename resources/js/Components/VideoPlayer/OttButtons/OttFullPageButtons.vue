<template>
    <div v-if="videoPlayerStore.ottButtons">

        <div class="ottButtonsContainer" id="ottButtons">
            <button
                v-if="userStore.prevUrl"
                @click="back"
                class="ottButton bg-gray-400 text-white hover:bg-gray-600 hover:text-gray-300">
                <font-awesome-icon icon="fa-angle-left" class="ml-2 text-3xl mb-1"/><div>BACK</div>
            </button>
<!--            <button-->
<!--                v-if="urlPrev !== 'empty'"-->
<!--                @click="back"-->
<!--                class="ottButton bg-gray-400 text-white hover:bg-gray-600 hover:text-gray-300">-->
<!--                <font-awesome-icon icon="fa-angle-left" class="ml-2 text-3xl mb-1"/><div>BACK</div>-->
<!--            </button>-->
            <button
                    v-if="userStore.isVip || userStore.isAdmin"
                    @click="openChannels"
                    class="ottButton bg-green-400 text-white hover:bg-green-600 hover:text-green-300">
                <font-awesome-icon icon="fa-rocket" class="ml-3.5 text-3xl mb-1"/><div>CHANNELS</div>
            </button>

            <button v-if="userStore.isSubscriber || userStore.isVip || userStore.isAdmin"
                    @click="openPlaylist"
                    class="ottButton bg-orange-400 text-white hover:bg-orange-600 hover:text-orange-300">
                <font-awesome-icon icon="fa-list" class="ml-2 text-3xl mb-1"/><div>PLAYLIST</div>
            </button>

            <button @click="openChat"
                    class="ottButton bg-blue-400 text-white hover:bg-blue-600 hover:text-blue-300">
                <font-awesome-icon icon="fa-comments" class="text-3xl mb-1"/><div>CHAT</div>
            </button>

            <button v-if="userStore.isVip || userStore.isAdmin"
                    @click="openFilters"
                    class="ottButton bg-yellow-400 text-white hover:bg-yellow-600 hover:text-yellow-300">
                <font-awesome-icon icon="fa-filter" class="ml-1 text-3xl mb-1"/><div>FILTERS</div>
            </button>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, computed } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()
let urlPrev = usePage().props.value.urlPrev

const previousURL = ref(null);

onMounted(() => {

});

function back() {
    // if (urlPrev !== 'empty') {
    //     Inertia.visit(urlPrev)
    // }

    if (!videoPlayerStore.currentPageIsStream) {
        videoPlayerStore.makeVideoTopRight()
        videoPlayerStore.fullPage = false
        videoPlayerStore.controls = false
    } else if (userStore.prevUrl) {
         // Inertia.visit(urlPrev)
         Inertia.visit(userStore.prevUrl)
      }


}

function openChannels() {
    videoPlayerStore.toggleChannels()
    // videoPlayerStore.controls = false
}

function openPlaylist() {
    videoPlayerStore.togglePlaylist()
    // videoPlayerStore.controls = false
}
function openChat() {
    videoPlayerStore.toggleChat()
    // videoPlayerStore.ottButtons = false
    // videoPlayerStore.controls = false
}

function openFilters() {
    videoPlayerStore.toggleFilters()
    // videoPlayerStore.controls = false
}
</script>
