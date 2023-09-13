<template>

    <Head :title="`Administrative Settings`"/>

    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="w-fit lg:w-3/4 left-0 right-0 mx-auto bg-white dark:bg-gray-800 rounded text-black dark:text-gray-50 mt-6 p-5">

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

            <div class="bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-6 mx-2 border-b border-2">
                <div class="font-semibold text-xl pb-2">Administrator only links</div>
                <div class="flex flex-wrap md:flex-row justify-items-start gap-2">
                    <!--disable button if ! admin-->
                    <Link :href="`/users`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
                    >All Users</button>
                    </Link>
                    <Link :href="`/admin/shows`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
                    >All Shows</button>
                    </Link>
                    <Link :href="`/admin/teams`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
                    >All Teams</button>
                    </Link>
                    <!--disable button if ! admin-->
                    <Link :href="`/admin/channels`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >All Channels</button>
                    </Link>
                    <!--disable button if ! admin-->
                    <Link :href="`/admin/mistServerApi`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >MistServer API</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/admin/images`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >Images</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/videoupload`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >Video Upload</button>
                    </Link>

                    <Link
                        :href="`/movies/create`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Add a Movie</button>
                    </Link>
                    <Link
                        :href="`/admin/invite_codes`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Invite Codes</button>
                    </Link>
<!--                    <Link-->
<!--                        :href="`/admin/phpmyinfo`"><button-->
<!--                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
<!--                    >phpinfo()</button>-->
<!--                    </Link>-->

                </div>

            </div>

            <div>
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="cdn_endpoint"
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
                               for="cloud_folder"
                        >
                            CLOUD FOLDER
                        </label>

                        <div class="flex flex-row">
                            <span class="pt-2 mr-2">/ </span>
                            <input v-model="form.cloud_folder"
                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                   type="text"
                                   name="cloud_folder"
                                   id="cloud_folder"
                            >
                        </div>
                        <span class="text-xs">NOTE: The forward slash is already entered in the backend. Just type the folder name.</span>

                        <div v-if="form.errors.cloud_folder" v-text="form.errors.cloud_folder"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="cloud_folder"
                        >
                            FIRST PLAY VIDEO SOURCE
                        </label>

                        <div class="flex flex-row">
                            <input v-model="form.first_play_video_source"
                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                   type="text"
                                   name="first_play_video_source"
                                   id="first_play_video_source"
                            >
                        </div>
                        <span class="text-xs"></span>

                        <div v-if="form.errors.first_play_video_source" v-text="form.errors.first_play_video_source"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="cloud_folder"
                        >
                            FIRST PLAY VIDEO SOURCE TYPE
                        </label>

                        <div class="flex flex-row">
                            <input v-model="form.first_play_video_source_type"
                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                   type="text"
                                   name="first_play_video_source"
                                   id="first_play_video_source"
                            >
                        </div>
                        <span class="text-xs">e.g., video/mp4 or application/x-mpegURL</span>

                        <div v-if="form.errors.first_play_video_source" v-text="form.errors.first_play_video_source"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="cloud_folder"
                        >
                            VIDEO NAME
                        </label>

                        <div class="flex flex-row">
                            <input v-model="form.first_play_video_name"
                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                   type="text"
                                   name="first_play_video_name"
                                   id="first_play_video_name"
                            >
                        </div>
                        <span class="text-xs"></span>

                        <div v-if="form.errors.first_play_video_name" v-text="form.errors.first_play_video_name"
                             class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                               for="cloud_folder"
                        >
                            CHANNEL ID
                        </label>

                        <div class="flex flex-row">
                            <input v-model="form.first_play_channel_id"
                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                   type="text"
                                   name="first_play_channel_id"
                                   id="first_play_channel_id"
                            >
                        </div>
                        <span class="text-xs text-orange-500">This needs to become a dropdown of channels to select.</span>

                        <div v-if="form.errors.first_play_channel_id" v-text="form.errors.first_play_channel_id"
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


                <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
                    <span>Change Max Video Upload Size. convert Bytes to KB, MB, GB, TB.</span>
                    <span>Update Video Uploader with the size.</span>
                </div>

                <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
                    Content licenses
                </div>

                <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
                    Episode Statuses
                </div>

                <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
                    Show Statuses
                </div>

                <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
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

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(async () => {
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

let props = defineProps({
    id: BigInt,
    cdn_endpoint: String,
    cloud_folder: String,
    first_play_video_source: String,
    first_play_video_source_type: String,
    first_play_video_name: String,
    first_play_channel_id: String,
    message: String
});

let form = useForm({
    id: props.id,
    cdn_endpoint: props.cdn_endpoint,
    cloud_folder: props.cloud_folder,
    first_play_video_source: props.first_play_video_source,
    first_play_video_source_type: props.first_play_video_source_type,
    first_play_video_name: props.first_play_video_name,
    first_play_channel_id: props.first_play_channel_id,
})

let submit = () => {
    form.put(route('admin.settings'));
};

let showMessage = ref(true);

</script>
