<template>

    <Head :title="`Edit Episode: ${props.episode.name}`"/>

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-dark text-light p-5 mb-10">

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
                            <div v-if="form.errors.video_file_embed_code" v-text="form.errors.video_file_embed_code"
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
                                <div>


                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="name"
                                        >
                                            Change Episode Poster
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">

                                            <h2 class="text-xl font-semibold text-gray-800">Upload Poster</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max File Size: <span class="text-orange-400">10MB</span></li>
                                                <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
                                            </ul>

                                            <div class="flex space-y-3">
                                                <div class="mb-6">
                                                    <img :src="'/storage/images/' + props.poster"
                                                         :key="poster" />
                                                </div>
                                            </div>

                                            <file-pond
                                                name="poster"
                                                ref="pond"
                                                label-idle="Click to choose image, or drag here..."
                                                @init="filepondInitialized"
                                                server="/showEpisodesUploadPoster"
                                                accepted-file-types="image/jpg, image/jpeg, image/png"
                                                @processfile="handleProcessedFile"
                                                max-file-size="10MB"
                                            />
                                        </div>

                                    </div>

                                    <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                           for="name"
                                    >
                                        Episode Video
                                    </label>

                                    <div class="flex justify-center w-full bg-black py-0">
                                        <!--                                <img :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">-->

                                        <!--                TEST VIDEO EMBED FROM RUMBLE             -->
                                        <!--                <iframe class="rumble" width="640" height="360" src="https://rumble.com/embed/v1nf3s7/?pub=4" frameborder="0" allowfullscreen></iframe>-->

                                        <div
                                            class="flex justify-center shadow overflow-hidden border-b border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">

                                            <img v-if="!props.episode.video_file_url && !props.episode.video_file_embed_code && props.episode.poster" :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">
                                            <img v-if="!props.episode.video_file_url && !props.episode.video_file_embed_code && !props.episode.poster" :src="`/storage/images/EBU_Colorbars.svg.png`" alt="" class="w-1/2 mx-2">

                                            <iframe v-if="props.episode.video_file_url && !props.episode.video_file_embed_code"
                                                    class="rumble" width="640" height="360" :src="`${props.episode.video_file_url}`" frameborder="0" allowfullscreen>
                                            </iframe>
                                            <div v-if="!props.episode.video_file_url && props.episode.video_file_embed_code" v-html="props.episode.video_file_embed_code">
                                            </div>
                                            <div v-if="props.episode.video_file_url && props.episode.video_file_embed_code" v-html="props.episode.video_file_embed_code">
                                            </div>
                                        </div>

                                    </div>


                                </div>


<!--Right Column-->
                                <div class="xl:col-span-2">

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="name"
                                        >
                                            Episode Notes (only the team members see the notes)
                                        </label>

                                        <input v-model="form.notes"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="notes"
                                               id="notes"
                                               required
                                        >
                                        <div v-if="form.errors.notes" v-text="form.errors.notes"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>


                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
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
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="episode_number"
                                        >
                                            Episode Number
                                        </label>

                                        <input v-model="form.episode_number"
                                               class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                               type="text"
                                               name="episode_number"
                                               id="episode_number"
                                        >
                                        <div v-if="form.errors.episode_number" v-text="form.errors.episode_number"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>



                                    <div class="mb-6 w-full">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
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


                                    <div class="block mt-12 mb-2 uppercase font-bold text-xs text-light text-red-700">
                                        Notes about video embedding:
                                    </div>
                                    <ul class="list-decimal pb-2 ml-2 mb-4 border-b">
                                        <li>
                                            The system will use the embed code if both URL and Embed code are provided.
                                        </li>
                                        <li>
                                            We have not enabled the use of Facebook videos for security purposes.
                                        </li>
                                    </ul>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="video_file_url"
                                        >
                                            Episode Video URL (if hosted externally)
                                        </label>

                                        <input v-model="form.video_file_url"
                                               class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                               type="text"
                                               name="video_file_url"
                                               id="video_file_url"
                                        >
                                        <div v-if="form.errors.video_file_url" v-text="form.errors.video_file_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="video_file_embed_code"
                                        >
                                            Episode Video Embed Code (if hosted externally)
                                        </label>

                                        <TabbableTextarea v-model="form.video_file_embed_code"
                                                          class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                          type="text"
                                                          name="video_file_embed_code"
                                                          id="video_file_embed_code"
                                                          rows="10" cols="30"
                                        />
                                        <div v-if="form.errors.video_file_embed_code" v-text="form.errors.video_file_embed_code"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>


                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="video_file_embed_code"
                                        >
                                            Add a video that has been uploaded to not.tv
                                        </label>
                                        <span class="italic">This feature is coming soon.</span>
                                    </div>



                                </div>
<!-- End Right Column -->
                            </div>
<!-- End grid 2-col -->

                                <div class="flex justify-end mb-6">
                                    <button
                                        @click="submit"
                                        class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
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
import TabbableTextarea from "@/Components/TabbableTextarea"
import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import ShowEpisodeEditHeader from "@/Components/ShowEpisodes/Edit/ShowEpisodeEditHeader"
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'episodes'
teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
showStore.episodePoster = props.poster;

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

let props = defineProps({
    episode: Object,
    show: Object,
    team: Object,
    poster: String,
});

let form = useForm({
    id: props.episode.id,
    name: props.episode.name,
    episode_number: props.episode.episode_number,
    description: props.episode.description,
    notes: props.episode.notes,
    show_id: props.episode.show_id,
    video_thumbnail: props.episode.video_thumbnail,
    video_file_url: props.episode.video_file_url,
    video_file_embed_code: props.episode.video_file_embed_code,
});

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

////////
// tec21: this isn't working...
// I wasn't able to set metadata
// or get metadata back from filepond.
//
// FilePond.setOptions = ({
//     fileMetadataObject: {
//         episode_id: '1',
//     },
// });

function filepondInitialized() {
    console.log("Filepond is ready!");
    console.log('Filepond object:', FilePond);
}

function handleProcessedFile(error, file) {
    if (error) {
        console.log("Filepond processed file");
        console.log(error);
        console.log(file);
        return;
    }
    Inertia.reload({
        only: ['poster'],
    });
}

let submit = () => {
    form.put(route('showEpisodes.update', props.episode.slug));
};

let showMessage = ref(true);

</script>
