<template>

    <Head :title="`Edit Movie: ${movie.name}`"/>

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-dark text-light p-5 mb-10">

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message">
                <span class="font-medium">
                                    {{ props.message }}
                                </span>
            </div>
            <header>


                <!--                <ShowEpisodeEditHeader :show="props.show" :team="props.team" :episode="props.episode"/>-->
                <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4">
                    <button
                        @click="back"
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel
                    </button>
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
                            <div v-if="form.errors.file_url" v-text="form.errors.file_url"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <!--                            <div v-if="form.errors.video_file_embed_code" v-text="form.errors.video_file_embed_code"-->
                            <!--                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->


                            <form @submit.prevent="submit">

                                <div class="mb-6">
                                    <label class="block mb-2 uppercase font-bold text-xs text-light"
                                           for="name"
                                    >
                                        Movie Name
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

                                <div class="mb-6 w-64">
                                    <label class="block mb-2 uppercase font-bold text-xs text-light"
                                           for="release_date"
                                    >
                                        Release Year
                                    </label>

                                    <input v-model="form.release_year"
                                           class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                           type="number"
                                           :placeholder="props.movie.release_year"
                                           name="release_year"
                                           id="release_year"
                                           minlength="4"
                                           maxlength="4"

                                    >
                                    <div v-if="form.errors.release_year" v-text="form.errors.release_year"
                                         class="text-xs text-red-600 mt-1"></div>
                                </div>

                                <div class="mb-6">
                                    <label class="block mb-2 uppercase font-bold text-xs text-light"
                                           for="category"
                                    >
                                        Category
                                    </label>

                                    <select class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs "
                                            v-model="form.category" @change="chooseCategory($event)"
                                    >
                                        <option v-for="category in props.categories"
                                                :key="category.key" :value="category.id">{{category.name}}</option>


                                    </select>
<!--    This was for practice... the next step is to loop over the sub-categories that belongTo the category selected. -->
<!--                                    <select>-->
<!--                                        <option v-for="option in options" :value="option.value">{{option.text}}</option>-->
<!--                                    </select>-->
                                    <div v-model="category"></div>{{movieStore.category_description}}
                                    <div v-if="form.errors.category" v-text="form.errors.category"
                                         class="text-xs text-red-600 mt-1"></div>
                                </div>

                                <div class="mb-6">
                                    <label class="block mb-2 text-gray-600 uppercase font-bold text-xs text-light"
                                           for="sub_category"
                                    >
                                        Sub-category
                                    </label>

                                    <select disabled class="border border-gray-400 text-gray-800 disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs"
                                            v-model="form.sub_category"
                                    >
                                        <option value="1">Option</option>
                                    </select>
                                    <div v-if="form.errors.sub_category" v-text="form.errors.sub_category"
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

                                <div class="flex justify-between">


                                </div>

                                <!-- Begin grid 2-col -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

                                    <!--Left Column-->
                                    <div>


                                        <div>
                                            <label class="block mb-2 uppercase font-bold text-xs text-light"
                                                   for="name"
                                            >
                                                Change Movie Poster
                                            </label>
                                            <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">

                                                <h2 class="text-xl font-semibold text-gray-800">Upload Poster</h2>

                                                <ul class="pb-4 text-gray-800">
                                                    <li>Max File Size: <span class="text-orange-400">10MB</span></li>
                                                    <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span>
                                                    </li>
                                                </ul>

                                                <!--                                                <div class="flex space-y-3">-->
                                                <!--                                                    <div class="mb-6">-->
                                                <!--                                                        <img :src="'/storage/images/' + props.poster"-->
                                                <!--                                                             :key="poster" />-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->

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


                                    </div>


                                    <!--Right Column-->
                                    <div>


                                        <div>
                                            <label class="block mb-2 uppercase font-bold text-xs text-light"
                                                   for="name"
                                            >
                                                Upload Video
                                            </label>
                                            <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark">
                                                <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

                                                <ul class="pb-4 text-gray-800">
                                                    <li>Max Video Length: <span class="text-orange-400">4 hours</span>
                                                    </li>
                                                    <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span>
                                                    </li>
                                                </ul>
                                                <!--                                                <div class="flex space-y-3">-->
                                                <!--                                                    <div class="mb-6">-->
                                                <!--                                                        <img v-if="!props.episode.video_thumbnail"-->
                                                <!--                                                             :src="'/storage/images/EBU_Colorbars.svg.png'"-->
                                                <!--                                                             :key="video_thumbnail" />-->

                                                <!--                                                        <img v-if="props.episode.video_thumbnail"-->
                                                <!--                                                             :src="'/storage/images/' + props.episode.video_thumbnail"-->
                                                <!--                                                             :key="video_thumbnail" />-->
                                                <!--                                                    </div>-->
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

                                    <div class="block mb-2 uppercase font-bold text-xs text-light">
                                        Notes:
                                    </div>
                                    <ul class="list-decimal pb-2 ml-2 mb-4 border-b">
                                        <li>
                                            We have not enabled the use of Facebook videos for security purposes.
                                        </li>
                                    </ul>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light"
                                               for="file_url"
                                        >
                                            Change Video URL (if hosted externally)
                                        </label>

                                        <input v-model="form.file_url"
                                               class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                               type="text"
                                               name="file_url"
                                               id="file_url"
                                        >
                                        <div v-if="form.errors.file_url" v-text="form.errors.file_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 text-gray-300"
                                               for="name"
                                        >
                                            Website URL
                                        </label>

                                        <input v-model="form.www_url"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="www_url"
                                               id="www_url"
                                        >
                                        <div v-if="form.errors.www_url" v-text="form.errors.www_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 text-gray-300"
                                               for="name"
                                        >
                                            Instagram @
                                        </label>

                                        <input v-model="form.instagram_name"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="instagram_name handle"
                                               id="instagram_name"
                                        >
                                        <div v-if="form.errors.instagram_name" v-text="form.errors.instagram_name"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 text-gray-300"
                                               for="name"
                                        >
                                            Telegram URL
                                        </label>

                                        <input v-model="form.telegram_url"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="telegram_url"
                                               id="telegram_url"
                                        >
                                        <div v-if="form.errors.telegram_url" v-text="form.errors.telegram_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 text-gray-300"
                                               for="name"
                                        >
                                            Twitter @
                                        </label>

                                        <input v-model="form.twitter_handle"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="twitter_handle"
                                               id="twitter_handle"
                                        >
                                        <div v-if="form.errors.twitter_handle" v-text="form.errors.twitter_handle"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>


                                </div>
                            </form>
                                <!-- End Right Column -->

                            <div class="flex justify-end my-6 mr-6">
                                <button
                                    @click="submit"
                                    class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                                    :disabled="form.processing"
                                >
                                    Save
                                </button>
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
import { ref, onBeforeMount, onMounted } from "vue"
import { useForm } from "@inertiajs/inertia-vue3"
import TabbableTextarea from "@/Components/TabbableTextarea"
import ShowEpisodeEditHeader from "@/Components/ShowEpisodes/Edit/ShowEpisodeEditHeader"

import {Inertia} from "@inertiajs/inertia"
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useTeamStore} from "@/Stores/TeamStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
import {useUserStore} from "@/Stores/UserStore";
import {useMovieStore} from "@/Stores/MovieStore";

import vueFilePond, {setOptions} from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'


let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()
let movieStore = useMovieStore()

videoPlayerStore.currentPage = 'movies'

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
    movie: Object,
    poster: String,
    categories: Object,
    sub_categories: Object,
});

// let category = ref();

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

function chooseCategory(event) {
movieStore.category_description =  props.categories[event.target.selectedIndex].description;
// next step is to add sub-categories and loop over them based on the selected category.
// this was for practice:
// const options = []
//     Array.from(event.target.selectedOptions).forEach(item => {
//         options.push({
//             value: item.value,
//             text: `You have selected ${item.text}`
//         })
//     })
//     this.options = options
//     console.log(event.target.selectedOptions);
}

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

// showStore.episodePoster = props.poster;

let form = useForm({
    id: props.movie.id,
    name: props.movie.name,
    release_year: props.release_year,
    category: props.movie.category,
    sub_category: props.movie.sub_category,
    description: props.movie.description,
    user_id: props.movie.user_id,
    team_id: props.movie.team_id,
    poster: props.movie.poster,
    file_url: props.movie.file_url,
    www_url: props.movie.www_url,
    instagram_name: props.movie.instagram_name,
    telegram_url: props.movie.telegram_url,
    twitter_handle: props.movie.twitter_handle,
});

let submit = () => {
    form.put(route('movies.update', props.movie.slug));
};

function back() {
    window.history.back()
}


</script>
