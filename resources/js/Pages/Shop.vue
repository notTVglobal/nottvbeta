<template>

    <Head title="Shop" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">


            <div v-if="props.can.viewCreator" class="flex flex-wrap-reverse justify-end gap-2">
                <Link :href="`#`"><button
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                    disabled
                >Add Product</button>
                </Link>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>
            <header class="flex justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-semibold pb-3">Shop</h1>
                </div>
            </header>

            <div class="mb-4">
                <div class="p-2 text-red-600">This section is in development. Not currently working.</div>
                <p class="">
                    Our notTV Shop... Add sponsors, who add commercials for products, services and events.
                </p>
            </div>

            <div class="flex flex-row justify-end gap-x-4 mb-4">

                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>

            <div class="bg-orange-300 px-2">
                Display items, services and events here.
            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { onMounted } from "vue"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    can: Object,
})

</script>

