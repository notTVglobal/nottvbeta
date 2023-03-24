<template>

    <Head :title="`Upload Video for ${props.episode.name}`"/>

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-gray-800 dark:bg-gray-800 dark:text-white p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="bg-black text-red-600 font-bold text-xl p-4 mb-4 w-full text-center">
                This page needs updating.. attach a video that is already uploaded or upload a new video for this episode.<br>
                Also, upload bonus content?
            </div>

            <header>
                <div class="flex justify-between mb-6">
                    <div>
                        <div class="font-bold mb-4 text-orange-700">UPLOAD VIDEO</div>
                        <h1 class="text-3xl">
                            <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="text-orange-700 font-bold">{{ episode.name }}</Link>
                        </h1>
                    </div>
                    <div>
                        <button
                            @click="back"
                            class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                        >Cancel
                        </button>
                    </div>
                </div>
                <div>
                    <div class=""><span class="text-xs uppercase font-semibold">Show: </span>
                        <Link :href="`/shows/${show.slug}/manage`">
                            {{ show.name }}
                        </Link>
                    </div>
                    <div class=""><span class="text-xs uppercase font-semibold">Team: </span>
                        <Link :href="`/teams/${team.slug}/manage`">
                            {{ team.name }}
                        </Link>
                    </div>
                    <div class="mb-6"><span class="text-xs uppercase font-semibold">Episode #: </span>
                        <span v-if="episode.episode_number">{{ episode.episode_number }}</span>
                        <span v-if="!episode.episode_number">{{ episode.id }}</span>
                    </div>
                </div>
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



<!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

<!--Right Column-->
                                <div>
                                    <div>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">
                                            <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                                                In development. Not currently working.
                                            </div>
                                            <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max Video Length: <span class="text-orange-400">4 hours</span></li>
                                                <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span></li>
                                            </ul>
                                            <div class="flex space-y-3">
                                                <div class="mb-6">
                                                    <img v-if="!props.episode.video_thumbnail"
                                                         :src="'/storage/images/EBU_Colorbars.svg.png'"
                                                         :key="video_thumbnail" />

                                                    <img v-if="props.episode.video_thumbnail"
                                                         :src="'/storage/images/' + props.episode.video_thumbnail"
                                                         :key="video_thumbnail" />
                                                </div>
                                            </div>

<!--                                            <file-pond-->
<!--                                                name="poster"-->
<!--                                                ref="pond"-->
<!--                                                label-idle="Click to choose video, or drag here..."-->
<!--                                                @init="filepondInitialized"-->
<!--                                                server="/showEpisodesUploadPoster"-->
<!--                                                accepted-file-types="image/jpg, image/jpeg, image/png"-->
<!--                                                @processfile="handleProcessedFile"-->
<!--                                                max-file-size="10MB"-->
<!--                                            />-->
                                        </div>

                                    </div>

                                    <JetValidationErrors class="mr-4" />

                                </div>
<!-- End Right Column -->
                            </div>
<!-- End grid 2-col -->


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
import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

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
    message: String,
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

function back() {
    window.history.back()
}

</script>
