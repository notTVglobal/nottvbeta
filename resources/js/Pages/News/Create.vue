<template>

    <Head title="Create News Post" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex flex-row justify-between">
                <h2 id="topDiv" class="text-xl font-semibold leading-tight text-gray-800">
                    Create News Post
                </h2>
                <Link
                    :href="`/newsroom`"><button
                    class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    v-if="props.can.viewNewsroom"
                >Newsroom</button>
                </Link>
            </div>

            <div class="p-6 bg-white border-b border-gray-200">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label
                            for="Title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >Title</label
                        >
                        <input
                            type="text"
                            v-model="form.title"
                            name="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.title"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </div>
                    </div>
                    <div class="mb-6">
                        <label
                            for="slug"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >Content</label
                        >
                        <textarea
                            type="text"
                            v-model="form.content"
                            name="content"
                            id=""
                            rows=10
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        ></textarea>

                        <div
                            v-if="form.errors.content"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.content }}
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                        :disabled="form.processing"
                        :class="{ 'opacity-25': form.processing }"
                    >
                        Submit
                    </button>
                    <Link :href="`/news`"><button
                        class="ml-2 px-4 py-2 text-white bg-blue-500 hover:bg-blue-300 rounded-lg"
                    >Cancel</button>
                    </Link>
                </form>
            </div>


        </div>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useForm } from '@inertiajs/inertia-vue3'
import {onMounted} from "vue";

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'news'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    document.getElementById("topDiv").scrollIntoView()
});

const props = defineProps({
    news: {
        type: Object,
        default: () => ({}),
    },
    can: Object,
});

const form = useForm({
    title: '',
    content: '',
});

const submit = () => {
    form.post(route("news.store"));
};

</script>
