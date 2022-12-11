<template>

    <Head title="News Post" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col ">
        <div class="bg-white text-black p-5 mb-10">

            <header class="mb-5 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">

                    <div
                        class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                        role="alert"
                        v-if="props.message"
                    >
                                <span class="font-medium">
                                    {{props.message}}
                                </span>
                    </div>


                    <div class="flex flex-col lg:flex-row justify-between items-center w-full">
                        <div class="">
                            <h1 class="text-xl font-semibold"><Link :href="`/news`" class="hover:text-blue-800">&lt; Back to News</Link></h1>
                            <ul class="flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8" >
                                <li>
                                    <Link :href="``" class="text-gray-700 cursor-not-allowed hidden">Categories</Link>
                                </li>
                                <li>
                                    <Link :href="`/news#cities`" class="hover:text-blue-800 hidden">Cities</Link>
                                </li>
                            </ul>
                        </div>


                            <div class="space-x-2 space-y-2 justify-end">
                                <Link
                                    :href="`/newsroom`"><button
                                    class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 px-4 py-2 rounded disabled:bg-gray-400"
                                    v-if="props.can.viewNewsroom"
                                >Newsroom</button>
                                </Link>
                                <Link :href="`/news/${props.news.slug}/edit`"><button
                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    v-if="can.editNewsPost"
                                >Edit</button>
                                </Link>
                                <Button
                                    class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                                    @click="destroy(news.id)"
                                    v-if="can.deleteNewsPost"
                                >
                                    Delete
                                </Button>
                            </div>

                        </div>


                </div>

            </header>




            <div class="flex justify-between flex-wrap-reverse md:mb-3">
                <div class="text-center lg:text-left w-full mb-4 lg:ml-6">
                    <h2 class="text-3xl font-semibold leading-tight text-gray-800">
                        {{ news.title }}
                    </h2>
                </div>

            </div>


            <div class="flex flex-col lg:flex-row items-start justify-items-center">
                <div class="flex items-start">
                    <img :src="`/storage/images/${props.image}`" class="object-scale-down md:max-w-sm px-6 mb-4 mx-auto">
                </div>

                <div class="px-6 bg-white border-b border-gray-200">
                        <div v-html="props.news.content" class="text-left mb-6 whitespace-pre-wrap">
                        </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {onBeforeMount, onMounted} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'news'

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
    image: String,
    message: String,
    can: Object,
});

let form = useForm({

});

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('news.destroy', id));

    }
}

</script>
