<template>
    <Head title="Go Live" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between">
                <div class="grid grid-cols-1 grid-rows-2 pt-4">
                    <h1 class="text-3xl font-semibold">Invite a Creator</h1>
                </div>
                <div class="grid grid-cols-1 grid-rows-2">
                    <div class="justify-self-end">
                        <Link :href="`/dashboard`"><button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Dashboard</button>
                        </Link>
                    </div>
                </div>
            </div>
                            <p>
                                Here we will place a form to invite a creator..
                            </p>
                            <ul class="list-disc list-inside pt-4 space-y-1">
                                <li>
                                    notTV Membership is by invitation.
                                </li>

                            </ul>
                        </div>
                    </div>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {onBeforeMount, onMounted} from "vue";
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'invite'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

</script>

