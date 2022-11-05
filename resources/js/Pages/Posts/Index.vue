<template>

    <Head title="Posts" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Posts</h1>

            <div class="mb-4">
                Events, shows, episodes, movies, news, channel updates, announcements, etc.
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

            <div class="flex flex-row justify-end gap-x-4 mb-4">

                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                                <th v-if="props.can.editPost" scope="col" class="px-6 py-3">
                                    Edit
                                </th>
                                <th v-if="props.can.editPost" scope="col" class="px-6 py-3">
                                    Delete
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="post in posts.data"
                                :key="post.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <td
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    {{ post.id }}
                                </td>
                                <td
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    <Link :href="`/posts/${post.slug}`" class="text-blue-800 hover:text-blue-600">{{ post.title }}</Link>
                                </td>
                                <td v-if="post.can.editPost" class="px-6 py-4">
                                    <Link :href="`/posts/${post.id}/edit`"><button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    >Edit</button>
                                    </Link>
                                </td>
                                <td v-if="post.can.editPost" class="px-6 py-4">
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
                        <!-- Paginator -->
                        <Pagination :links="posts.links" class="mt-6"/>
                    </div>
                </div>
            </div>


        </div>

    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { useForm } from '@inertiajs/inertia-vue3'
import {onMounted, ref, watch} from "vue";
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    filters: Object,
    can: Object,
    posts: {
        type: Object,
        default: () => ({}),
    },
    message: String,
});

let search = ref(props.filters.search);

let form = useForm({

});




watch(search, throttle(function (value) {
    Inertia.get('/posts', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('posts.destroy', id));

    }
}

</script>
