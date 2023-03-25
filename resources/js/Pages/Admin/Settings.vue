<template>

    <Head :title="`Administrative Settings`"/>

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white dark:bg-gray-800 rounded text-black dark:text-gray-50 p-5">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <header>
                <div class="flex justify-between mb-3">
                    <div class="mb-4">
                        <h1 class="text-3xl font-semibold">Administrative Settings</h1>
                    </div>
                    <div>
                        <div class="flex flex-wrap-reverse justify-end gap-2">
                                <Link :href="`/dashboard`">
                                    <button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    >Dashboard
                                    </button>
                                </Link>
                        </div>
                    </div>
                </div>
            </header>

            <div>
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="name"
                        >
                            CDN ENDPOINT
                        </label>

                        <input v-model="form.cdn_endpoint"
                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                               type="text"
                               name="cdn_endpoint"
                               id="cdn_endpoint"
                        >
                        <div v-if="form.errors.cdn_endpoint" v-text="form.errors.cdn_endpoint"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="name"
                        >
                            CDN FOLDER
                        </label>

                        <div class="flex flex-row">
                            <span class="pt-2 mr-2">/ </span>
                            <input v-model="form.cdn_folder"
                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                   type="text"
                                   name="cdn_folder"
                                   id="cdn_folder"
                            >
                        </div>
                        <span class="text-xs">NOTE: The forward slash is already entered in the backend. Just type the folder name.</span>

                        <div v-if="form.errors.storage_folder" v-text="form.errors.storage_folder"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="flex justify-end my-6 mr-6">
                        <JetValidationErrors class="mr-4" />
                        <button
                            @click="submit"
                            class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                            :disabled="form.processing"
                        >
                            Save
                        </button>
                    </div>

                </form>


                <div>
                    Content licenses
                </div>
                <div>
                    Episode Statuses
                </div>
                <div>
                    Show Statuses
                </div>
                <div>
                    Channels. Display list of channels. Click channel name to go to the channel playlist edit page.
                    Grid style, 1 column mobile, 2 column tablet, 3 column lg, 4 column xl
                        Rows: Times (next 24 hours) --> can scroll up to 72 hours ahead or 72 hours back.
                        Columns: Days (next 7 days). Need new Pages folder: Channels/Index, Channels/Create, Channels/Edit, ch/channelId (e.g., ch/01).

                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'admin'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(async () => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    cdn_endpoint: String,
    cdn_folder: String,
    message: String
});

let form = useForm({
    cdn_endpoint: props.cdn_endpoint,
    cdn_folder: props.cdn_folder,
})

let submit = () => {
    form.post(route('admin.settings'));
};

let showMessage = ref(true);

</script>
