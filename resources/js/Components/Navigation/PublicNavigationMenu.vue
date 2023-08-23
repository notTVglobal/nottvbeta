<template>
    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('home')">
                            <JetApplicationMark class="block h-9 w-auto"/>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="space-x-8 py-6 pt-6 ml-8 lg:flex text-gray-200">
                        <h3 class="inline-flex items-center relative">
                        <Link
                            :href="`/public/news`"
                            class="hover:text-blue-500">
                                News
<!--                            <div v-if="streamStore.isLive"-->
<!--                                class="text-xs text-white bg-red-800 uppercase flex justify-center items-center absolute -right-4 top-1.5-->
<!--                                    font-semibold inline-block py-0.5 px-1 rounded last:mr-0 mr-1">-->
<!--                               live-->
<!--                            </div>-->
                        </Link>
                        </h3>
                        <h3 class="inline-flex items-center relative">
                        <Link
                            :href="`/`"
                            class="hover:text-blue-500">
                                Watch Now
                        </Link>
                        </h3>
                        <h3 class="inline-flex items-center relative">
                            <Link
                                :href="`/login`"
                                class="hover:text-blue-500">
                                Login
                            </Link>
                        </h3>
                        <h3 class="inline-flex items-center relative">
                            <Link
                                :href="`/register`"
                                class="hover:text-blue-500">
                                Register
                            </Link>
                        </h3>
                    </div>

                </div>



            </div>
        </div>
    </nav>
</template>

<script setup>
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import NotificationsButton from "@/Components/Navigation/NotificationsButton.vue";
import JetDropdown from '@/Jetstream/Dropdown.vue';
import {Inertia} from "@inertiajs/inertia";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import { useChatStore } from "@/Stores/ChatStore.js";
import { useStreamStore } from "@/Stores/StreamStore";
import { useUserStore } from "@/Stores/UserStore";
import { useWelcomeStore } from "@/Stores/WelcomeStore";

let chat = useChatStore();
let videoPlayerStore = useVideoPlayerStore();
let streamStore = useStreamStore()
let userStore = useUserStore()
let welcomeStore = useWelcomeStore()

streamStore.isLive(true)

let props = defineProps({
    user: Object,
})
// let isStreamPage = false
//
// function setPage() {
//     if (videoPlayerStore.currentPage = "stream") {
//         videoPlayerStore.currentPageIsStream = true;
//     } else
//         videoPlayerStore.currentPageIsStream = false;
// }

// setPage()

</script>
<style>

.isFullPageCss {
    background: rgba(0, 0, 0, 0.8);
    /*background: yellow;*/
}

</style>
