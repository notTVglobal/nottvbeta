<template>

    <Head :title="`Edit Episode: ${props.episode.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-white p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <header>
                <ShowEpisodeEditHeader :show="props.show" :team="props.team" :episode="props.episode"/>
            </header>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.episode_number" v-text="form.errors.episode_number"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.notes" v-text="form.errors.notes"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.video_file_url" v-text="form.errors.video_file_url"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.youtube_url" v-text="form.errors.youtube_url"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.video_embed_code" v-text="form.errors.video_embed_code"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>


                            <form @submit.prevent="submit">
<!--                                <div class="flex justify-end mr-2 mb-6">-->
<!--                                    <button-->
<!--                                        @click="submit"-->
<!--                                        class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"-->
<!--                                        :disabled="form.processing"-->
<!--                                    >-->
<!--                                        Save-->
<!--                                    </button>-->
<!--                                </div>-->



<!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 space-x-6 p-6">

<!--Left Column-->
                                <div class="xl:col-span-2">

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="notes"
                                        >
                                            Episode Notes (only the team members see the notes)
                                        </label>

                                        <input v-model="form.notes"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="notes"
                                               id="notes"
                                        >
                                        <div v-if="form.errors.notes" v-text="form.errors.notes"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>


                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="name"
                                        >
                                            Episode Name
                                        </label>

                                        <input v-model="form.name"
                                               class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                               type="text"
                                               name="name"
                                               id="name"
                                               required
                                        >
                                        <div v-if="form.errors.name" v-text="form.errors.name"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="episode_number"
                                        >
                                            Episode Number
                                        </label>

                                        <input v-model="form.episode_number"
                                               class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                               type="text"
                                               :placeholder="props.episode.id"
                                               name="episode_number"
                                               id="episode_number"
                                        >
                                        <div v-if="form.errors.episode_number" v-text="form.errors.episode_number"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>



                                    <div class="mb-6 w-full">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-white"
                                               for="description"
                                        >
                                            Description
                                        </label>
                                        <TabbableTextarea v-model="form.description"
                                                          class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                          name="description"
                                                          id="description"
                                                          rows="10" cols="30"
                                                          required
                                        />
                                        <div v-if="form.errors.description" v-text="form.errors.description"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="video_file_url"
                                        >
                                            YouTube URL
                                        </label>

                                        <input v-model="form.youtube_url"
                                               class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                               type="text"
                                               name="youtube_url"
                                               id="youtube_url"
                                        >
                                        <div v-if="form.errors.youtube_url" v-text="form.errors.youtube_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="video_file_url"
                                        >
                                            Episode Video URL of MP4 (External) - example: <span class="underline">https://domainname.com/filename.mp4</span>
                                        </label>

                                        <input v-model="form.video_url"
                                               class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                               type="text"
                                               name="video_file_url"
                                               id="video_file_url"
                                        >
                                        <div v-if="form.errors.video_url" v-text="form.errors.video_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                               for="video_embed_code"
                                        >
                                            Episode Video Embed Code (external) <span class="text-white">*</span>
                                        </label>

                                        <TabbableTextarea v-model="form.video_embed_code"
                                                          class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                          type="text"
                                                          name="video_embed_code"
                                                          id="video_embed_code"
                                                          rows="10" cols="30"
                                        />
                                        <div v-if="form.errors.video_embed_code" v-text="form.errors.video_embed_code"
                                             class="text-xs text-white mt-1"></div>
                                    </div>

                                    <div class="mt-2 mb-6 pb-4 border-b">
                                        <div class="mb-2 block uppercase font-bold text-xs">
                                            * Notes about video embedding:
                                        </div>
                                        <ul class="list-decimal pb-2 ml-2">
                                            <li>
                                                If both URL and Embed Code are provided the system will use the Embed Code.
                                            </li>
                                            <li>
                                                We have <span class="font-bold">not</span> enabled the use of Facebook videos for security purposes.
                                            </li>
                                        </ul>
                                    </div>


                                </div>
<!-- End Left Column -->

<!--Right Column-->
                                <div>

                                    <label class="block mb-2 uppercase font-bold text-xs text-white"
                                           for="name"
                                    >
                                        Episode Video
                                    </label>

                                    <div class="flex justify-center w-full bg-white dark:bg-black mb-6 py-0">


                                        <!--                TEST VIDEO EMBED FROM RUMBLE             -->
                                        <!--                <iframe class="rumble" width="640" height="360" src="https://rumble.com/embed/v1nf3s7/?pub=4" frameborder="0" allowfullscreen></iframe>-->

                                        <div
                                            v-if="!episode.video.file_name"
                                            class="flex justify-center shadow overflow-hidden border-b border-gray-200 w-full bg-white dark:bg-black text-2xl sm:rounded-lg p-5">
NO VIDEO
                                            <iframe v-if="props.episode.video_file_url && !props.episode.video_embed_code"
                                                    class="rumble" width="640" height="360" :src="`${props.episode.video_file_url}`" frameborder="0" allowfullscreen>
                                            </iframe>
                                            <div v-if="!props.episode.video_file_url && props.episode.video_embed_code" v-html="props.episode.video_embed_code">
                                            </div>
                                            <div v-if="props.episode.video_file_url && props.episode.video_embed_code" v-html="props.episode.video_embed_code">
                                            </div>
                                        </div>

                                        <div v-if="episode.video.file_name" class="mb-6 bg-black w-full p-6">
HAS VIDEO
                                            <span
                                                v-if="episode.video.upload_status === 'processing'"
                                                class="text-center place-self-center text-white font-semibold text-xl">Video processing...</span>
                                            <video v-if="episode.video.upload_status !== 'processing'" :src="episode.video.cdn_endpoint+episode.video.cloud_folder+episode.video.folder+'/'+episode.video.file_name" controls></video>
                                        </div>

                                    </div>

                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="episodeVideo"
                                        >
                                            Upload Episode
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">
                                            <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max Video Length: <span class="text-orange-400">4 hours</span>
                                                </li>
                                                <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span>
                                                </li>
                                            </ul>

                                            <VideoUpload :showEpisodeId="episode.id" class="text-black"/>

                                        </div>

                                    </div>


                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs text-white"
                                               for="name"
                                        >
                                            Change Episode Poster
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">

                                            <ImageUpload :image="props.image"
                                                         :server="'/showEpisodesUploadPoster'"
                                                         :name="'Upload Episode Poster'"
                                                         :maxSize="'20MB'"
                                                         :fileTypes="'image/jpg, image/jpeg, image/png'"
                                                         @reloadImage="reloadImage"
                                            />

                                            <div class="flex space-y-3">
                                                <div class="mb-6">
                                                    <SingleImage :image="props.image" :key="props.image"/>
                                                </div>
                                            </div>



                                        </div>

                                    </div>



                                </div>


<!-- End Right Column -->
                            </div>
<!-- End grid 2-col -->

                                <div class="flex justify-end mb-6">
                                    <JetValidationErrors class="mr-4" />
                                    <button
                                        @click="submit"
                                        class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4 mr-5"
                                        :disabled="form.processing"
                                    >
                                        Save
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useUserStore } from "@/Stores/UserStore";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TabbableTextarea from "@/Components/TabbableTextarea"
import ShowEpisodeEditHeader from "@/Components/ShowEpisodes/Edit/ShowEpisodeEditHeader"
import Message from "@/Components/Modals/Messages";
import ImageUpload from "@/Components/Uploaders/ImageUpload.vue";
import SingleImage from "@/Components/Multimedia/SingleImage.vue";
import VideoUpload from "@/Components/Uploaders/VideoUpload"

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'episodes'
teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
showStore.episodePoster = props.image.name;

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

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

let props = defineProps({
    show: Object,
    team: Object,
    episode: Object,
    image: Object,
    can: Object,
});

let form = useForm({
    id: props.episode.id,
    name: props.episode.name,
    episode_number: props.episode.episode_number,
    description: props.episode.description,
    notes: props.episode.notes,
    show_id: props.episode.show_id,
    video_url: props.episode.video_url,
    youtube_url: props.episode.youtube_url,
    video_embed_code: props.episode.video_embed_code,
});

let reloadImage = () => {
    Inertia.reload({
        only: ['image'],
    });
};

let submit = () => {
    form.put(route('showEpisodes.update', props.episode.slug));
};

let showMessage = ref(true);

</script>
