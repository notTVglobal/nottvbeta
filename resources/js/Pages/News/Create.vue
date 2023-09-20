<template>

    <Head title="Create News Post" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight">
                    Create News Post
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
                            for="content_json"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >Content</label>
                        <tiptap v-if="videoPlayerStore.currentPage === 'newsCreate'" />
<!--                        <textarea-->
<!--                            type="text"-->
<!--                            v-model="form.content_json"-->
<!--                            id=""-->
<!--                            name="content_json"-->
<!--                            rows=10-->
<!--                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"-->
<!--                        ></textarea>-->

                        <div
                            v-if="form.errors.content_json"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.content_json }}
                        </div>
                    </div>
                    <div class=" flex justify-start">
                    <button
                        type="submit"
                        class="h-fit text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                        :disabled="form.processing"
                        :class="{ 'opacity-25': form.processing }"
                    >
                        Submit
                    </button>
                    <Link :href="`/news`"><button
                        class="h-fit ml-2 px-4 py-2 text-white bg-blue-500 hover:bg-blue-300 rounded-lg"
                    >Cancel</button>
                    </Link>
                    <JetValidationErrors class="ml-4" />
                    </div>
                </form>
            </div>


        </div>

    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import {useForm, usePage} from '@inertiajs/inertia-vue3'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import { useNewsStore } from "@/Stores/NewsStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
// import TabbableTextarea from "@/Components/TabbableTextarea.vue";
import Tiptap from "@/Components/TextEditor/TiptapNewsPostCreate.vue";
import Message from "@/Components/Modals/Messages";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let newsStore = useNewsStore()

let props = defineProps({
    can: Object,
});

let form = useForm({
    title: '',
    body: '',
    content_json: [],
});

let submit = () => {
    form.body = newsStore.newsArticleContentTiptop;
    form.post(route("news.store"));
};

userStore.currentPage = 'newsCreate'
userStore.showFlashMessage = true;

onBeforeMount(() => {
    // userStore.scrollToTopCounter = 0;
    newsStore.newsArticleContentTiptop = [];
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
});


function back() {
    newsStore.newsArticleContentTiptop = '';
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

</script>
