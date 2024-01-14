<template>
    <Head title="Go Live" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <div class="flex justify-between">
                <div class="grid grid-cols-1 grid-rows-2 pt-4">
                    <h1 class="text-3xl font-semibold">Invite a Creator</h1>
                </div>
                <div class="grid grid-cols-1 grid-rows-2">
                    <div>
                        <button
                            @click="userStore.btnRedirect('/dashboard')"
                            class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                        >Dashboard
                        </button>
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
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'invite'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

</script>

