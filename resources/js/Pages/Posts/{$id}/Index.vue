<template>

    <Head title="Posts" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ props.post.title }}
                </h2>
                <div class="grid grid-cols-1 grid-rows-2">
                    <Link :href="`/posts`" class="mr-2 text-blue-800 hover:text-blue-600">All Posts</Link>
                    <Link :href="`/posts/${props.post.id}/edit`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Edit</button>
                    </Link>
                </div>
            </div>

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                    <span class="font-medium">
                        {{props.message}}
                    </span>
            </div>

            <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        {{ props.post.content }}
                    </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { useForm } from '@inertiajs/inertia-vue3'
import {onMounted} from "vue";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

const props = defineProps({
    post: {
        type: Object,
        default: () => ({}),
    },
    message: String
});

</script>
