<template>

    <div class="ottTopRightDisplay bg-gray-600 text-gray-50 pt-2">

        <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-yellow-600 text-black p-2">UPGRADE</h1>
        <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
             :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
            Upgrade to a Premium Subscription to gain access to more features, shows, news, movies and channels!
            <div class="relative top-0 left-0 right-0 w-fit mx-auto mt-2">
                <button class="rounded-lg py-1 px-2 bg-blue-800 hover:bg-blue-600 text-white"
                        @click="upgrade">
                    Upgrade now!
                </button>
            </div>
        </div>

    </div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";
import {useUserStore} from "@/Stores/UserStore";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

function upgrade() {
    Inertia.get('/upgrade')
    videoPlayerStore.ottClass='OttClose'
    videoPlayerStore.ott=0

}


</script>
