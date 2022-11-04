<template>
    <Head title="Go Live" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->

<!--        <div class="py-12">-->
<!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between">
                <div class="grid grid-cols-1 grid-rows-2">
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
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import {onMounted} from "vue";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

</script>

