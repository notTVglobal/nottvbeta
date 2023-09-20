<template>

    <Head title="News Post" />

    <div class="place-self-center flex flex-col">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-1 w-full">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="mb-5 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center px-4 py-6">


                        <div class="text-center lg:text-left mb-4 lg:ml-6">
                            <h2 class="text-3xl font-semibold leading-tight">
                                {{ news.title }}
                            </h2>
                            <div class="">{{ news.author }}</div>
                            <div class="font-light">{{ formatDate(news.published_at) }}</div>
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
                                <button
                                    @click="back"
                                    class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                                >Back
                                </button>
                        </div>



                </div>

            </header>



            <div class="w-full flex flex-wrap justify-end md:mb-3">
                <div class="text-right md:mb-3">

                </div>
            </div>



            <div class="w-max-w-full flex flex-wrap items-start justify-items-center">
                <div class="flex items-start">
                    <img :src="`/storage/images/${props.image}`" class="object-scale-down md:max-w-sm px-6 mb-4 mx-auto">
                </div>

                <div class="vw-100 overflow-x-none break-words flex flex-wrap px-6 border-b border-gray-200 news-content space-y-5">
<!--                        <div v-html="'<pre>'+news.content+'</pre>'" class="text-left mb-6 leading-loose font-['monospace']">-->
                        <div v-html="news.content" class="text-left mb-6 leading-loose font-['monospace']">
                        </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'news'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

const props = defineProps({
    news: Object,
    image: String,
    can: Object,
});

let form = useForm({

});

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('news.destroy', id));

    }
}

function back() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

</script>

<style scoped>
pre p {
    white-space: break-spaces;
    overflow-x: auto;
}

</style>
