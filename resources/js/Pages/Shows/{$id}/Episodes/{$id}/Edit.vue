<template>

    <Head :title="`Edit Episode: ${props.episode.name}`"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-dark text-light p-5 mb-10">

            <ShowEditHeader :show="props.show" :team="props.team" :episode="props.episode"/>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

<!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

<!--Left Column-->
                                <div>
                                    <div class="flex space-y-3">
                                        <div class="mb-6">
                                            <img :src="'/storage/images/' + props.poster"
                                                 :key="poster" />
                                        </div>
                                    </div>
                                </div>

<!--Right Column-->
                                <div>
                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs text-light"
                                               for="name"
                                        >
                                            Change Episode Poster
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">
                                            <h2 class="text-xl font-semibold text-gray-800">Upload Episode Poster</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max File Size: <span class="text-orange-400">10MB</span></li>
                                                <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
                                            </ul>
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

                                    <form @submit.prevent="submit">
                                        <div class="mb-6">
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light"
                                                   for="name"
                                            >
                                                Episode Name
                                            </label>

                                            <input v-model="form.name"
                                                   class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                   type="text"
                                                   name="name"
                                                   id="name"
                                                   required
                                            >
                                            <div v-if="form.errors.name" v-text="form.errors.name"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light"
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

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light"
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

                                        <div class="flex justify-between mb-6">
                                            <button
                                                type="submit"
                                                class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                                                :disabled="form.processing"
                                            >
                                                Save
                                            </button>
                                        </div>
                                    </form>

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
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import { onMounted } from "vue"
import {useForm} from "@inertiajs/inertia-vue3"
import TabbableTextarea from "@/Components/TabbableTextarea"

import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"

import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'

import ShowEditHeader from "@/Components/Shows/Edit/ShowEditHeader"

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    episode: Object,
    show: Object,
    team: Object,
    poster: String,
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

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
showStore.episodePoster = props.poster;

let form = useForm({
    id: props.episode.id,
    name: props.episode.name,
    episode_number: props.episode.episode_number,
    description: props.episode.description,
});

let submit = () => {
    form.put(route('showEpisodes.update', props.episode.slug));
};


</script>
