<template>

    <Head title="Edit News Post" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight">
                    Edit News Post
                </h2>
                <div class="flex justify-end space-x-2">
                    <Link
                        :href="`/newsroom`"><button
                        class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"
                        v-if="props.can.viewNewsroom"
                    >Newsroom</button>
                    </Link>
                    <div>
                        <button
                            @click="back"
                            class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                        >Cancel
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-200">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label
                            for="Title"
                            class="block mb-2 text-sm font-bold text-gray-900 dark:text-gray-300"
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
                            class="block mb-2 text-sm font-bold text-gray-900 dark:text-gray-300"
                        >Content</label>
                        <tiptap v-if="videoPlayerStore.currentPage === 'newsEdit'" />
<!--                        <tabbable-textarea-->
<!--                            type="text"-->
<!--                            v-model="form.content"-->
<!--                            name="content"-->
<!--                            id=""-->
<!--                            rows=10-->
<!--                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"-->
<!--                        ></tabbable-textarea>-->

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
                        Save
                    </button>
<!--                    <Link :href="`/news/${news.slug}`"><button-->
<!--                        class="ml-2 px-4 py-2 text-white bg-blue-500 hover:bg-blue-300 rounded-lg"-->
<!--                    >Cancel</button>-->
<!--                    </Link>-->
<!--                    <Link :href="`/news/${news.slug}`"><button-->
<!--                        class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 "-->
<!--                    >Save</button>-->
<!--                    </Link>-->
                </form>
            </div>

        </div>

    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useForm } from '@inertiajs/inertia-vue3'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import { useNewsStore } from "@/Stores/NewsStore"
// import TabbableTextarea from "@/Components/TabbableTextarea.vue";
import Tiptap from "@/Components/TextEditor/TiptapNewsPostEdit.vue";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let newsStore = useNewsStore()

videoPlayerStore.currentPage = 'newsEdit'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

const props = defineProps({
    news: Object,
    can: Object,
});

newsStore.newsArticleIdTiptop = props.news.id;
newsStore.newsArticleTitleTiptop = props.news.title;
newsStore.newsArticleContentTiptop = props.news.content;

// note: as of March 2023 the form submission cannot use "content" as
// a form field name. It conflicts with the HTMLRequest content item.
// Our news post content is called content in the database, but it is
// now called body in our form submission back to Laravel.
//
const form = useForm({
    id: props.news.id,
    title: props.news.title,
    body: newsStore.newsArticleContentTiptop,
});

form.body = newsStore.newsArticleContentTiptop;

const submit = () => {
    form.body = newsStore.newsArticleContentTiptop;
    form.put(route("news.update", props.news.id));
};

let showMessage = ref(true);

function back() {
    newsStore.newsArticleContentTiptop = '';
    window.history.back()
}

</script>
