<template>

    <Head title="Movies" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-semibold pb-3">Movies</h1>
                    <Link :href="`#`"><button
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                    >Add Movie</button>
                    </Link>
                </div>
                <div class="grid grid-cols-1 grid-rows-3">
                    <div class="justify-self-end mb-4">
                        <Link :href="`/dashboard`"><button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Dashboard</button>
                        </Link>
                    </div>
                    <div>
                        <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg row-start-2" />
                    </div>
                </div>
            </div>

            <p class="mb-8">
                Display a grid of movies and documentaries available for free, PPV and to purchase. Also search, and browse by category/date/popularity.
            </p>
            <div class="bg-orange-300 px-2">
                Display movies here.
            </div>

        </div>
    </div>

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


