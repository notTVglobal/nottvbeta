<template>
    <Head title="Training" />
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
                    <h1 class="text-3xl font-semibold">Training</h1>
                </div>
                <div><span class="text-xs font-semibold text-purple-700">Creator Mode</span></div>
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
                                Travis will create some training videos to help people create content in a higher broadcast quality.
                            </p>
                            <ul class="list-disc list-inside pt-4">
                                <li>
                                    Field production
                                </li>
                                <li>
                                    Studio production
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
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

</script>

