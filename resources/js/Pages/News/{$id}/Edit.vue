<template>

    <Head title="Edit News Post" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">
            <h2 id="topDiv" class="text-xl font-semibold leading-tight text-gray-800">
                Edit News Post
            </h2>

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
                            for="Slug"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >Slug</label
                        >
                        <input
                            type="text"
                            v-model="form.slug"
                            name="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.slug"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.slug }}
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
                        class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                        :disabled="form.processing"
                        :class="{ 'opacity-25': form.processing }"
                    >
                        Submit
                    </button>
                    <Link :href="`/news/${news.id}`"><button
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
});

const form = useForm({
    id: props.news.id,
    title: props.news.title,
    slug: props.news.slug,
    content: props.news.content,
});

const submit = () => {
    form.put(route("news.update", props.news.id));
};

</script>
