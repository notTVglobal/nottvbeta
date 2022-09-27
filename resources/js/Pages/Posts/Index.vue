<template>

    <Head title="Posts" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-3">
                <h1 class="text-3xl font-semibold pb-3">Posts</h1>
                <div class="grid grid-cols-1 grid-rows-2">
                    <div class="justify-self-end mb-4">
                        <Link :href="`/dashboard`"><button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Dashboard</button>
                        </Link>
                    </div>
                    <div>
                        <Link :href="`/posts/create`"><button
                        class="bg-green-500 hover:bg-green-600 text-white mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                        >Add Post</button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                Display our posts here of new events, blog items, etc.
            </div>

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
            >
                    <span class="font-medium">
                        Alerts go here.
                    </span>
            </div>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                    >
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Slug
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Edit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Delete
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="post in posts"
                                :key="post.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    {{ post.id }}
                                </th>
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    <Link :href="`/posts/${post.id}`" class="text-blue-800 hover:text-blue-600">{{ post.title }}</Link>
                                </th>
                                <td class="px-6 py-4">
                                    {{ post.slug }}
                                </td>


                                <td class="px-6 py-4">
                                    <Link :href="`/posts/${post.id}/edit`"><button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    >Edit</button>
                                    </Link>
                                </td>
                                <td class="px-6 py-4">
                                    <Button
                                        class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                                        @click="destroy(post.id)"
                                    >
                                        Delete
                                    </Button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import { useForm } from '@inertiajs/inertia-vue3'

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

const props = defineProps({
    posts: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm();

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('posts.destroy', id));
    }
}

</script>
