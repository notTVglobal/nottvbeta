<template>

    <Head title="Add RSS Feed" />

    <div class="place-self-center flex flex-col gap-y-3 mt-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight">
                    Add RSS Feed
                </h2>
                <div class="flex justify-end space-x-2">
                    <div>
                        <button
                            @click="back"
                            class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                        >Back
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-200">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label
                            for="Name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >Name</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            name="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>
                    <div class="mb-6">
                        <label
                            for="Url"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >URL</label
                        >
                        <input
                            type="text"
                            v-model="form.url"
                            name="url"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.url"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.url }}
                        </div>
                    </div>

                    <div class=" flex justify-start">
                        <button
                            type="submit"
                            class="h-fit text-white bg-blue-700 hover:bg-blue-300 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 "
                            :disabled="form.processing"
                            :class="{ 'opacity-25': form.processing }"
                        >
                            Submit
                        </button>
                        <Link :href="`/news`"><button
                            class="h-fit ml-2 px-4 py-2 text-white bg-blue-700 hover:bg-blue-300 rounded-lg"
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
import { onMounted, ref } from "vue";
import { useForm } from '@inertiajs/inertia-vue3'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import { useNewsStore } from "@/Stores/NewsStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import Button from "@/Jetstream/Button.vue";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let newsStore = useNewsStore()

videoPlayerStore.currentPage = 'newsRssCreate'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

const props = defineProps({
    can: Object,
    message: String,
});

let form = useForm({
    name: '',
    url: '',
});

let submit = () => {
    form.post(route("feeds.store"));
};

let showMessage = ref(true);

function back() {
    window.history.back()
}

</script>
