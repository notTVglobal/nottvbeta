<template>

    <Head :title="`Edit Movie: ${movie.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-dark text-light p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <header>
                <div class="flex justify-between mb-6">
                    <div>
                        <div class="font-bold mb-4 text-red-700">EDIT MOVIE</div>
                        <h1 class="text-3xl">
                            <Link :href="`/movies/${movie.slug}`" class="text-red-700 font-bold uppercase">{{ movie.name }}</Link>
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
            </header>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
                            <div v-if="form.errors.file_url" v-text="form.errors.file_url"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
                            <!--                            <div v-if="form.errors.video_file_embed_code" v-text="form.errors.video_file_embed_code"-->
                            <!--                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->


                            <!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">


                                <!--Left Column-->
                                <div>
                                    <div class="flex space-y-3">
                                        <div class="mb-6">
                                            <SingleImage :image="props.image" :key="props.image"/>
                                        </div>
                                    </div>

                                    <div class="w-full">

                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="name"
                                        >
                                            Change Movie Poster
                                        </label>

                                        <ImageUpload :image="props.image"
                                                     :server="'/moviesUploadPoster'"
                                                     :name="'Upload Movie Poster'"
                                                     :maxSize="'30MB'"
                                                     :fileTypes="'image/jpg, image/jpeg, image/png'"
                                                     @reloadImage="reloadImage"
                                        />

                                    </div>

                                    <div class="flex space-y-3">
                                        <div class="mb-6 bg-black w-full p-6">
                                            <span
                                                v-if="video.upload_status === 'processing'"
                                                class="text-center place-self-center text-white font-semibold text-xl">Video processing...</span>
                                            <video v-if="video.upload_status !== 'processing'" :src="video.cdn_endpoint+video.cloud_folder+video.folder+'/'+video.file_name" controls></video>
                                        </div>
                                    </div>

                                    <div class="">
                                        <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                               for="name"
                                        >
                                            Upload Movie
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 px-6 pt-4 pb-2 text-dark">
                                            <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max Video Length: <span class="text-orange-400">4 hours</span>
                                                </li>
                                                <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span>
                                                </li>
                                            </ul>

                                            <VideoUpload :movieId="movie.id" class="text-black"/>

                                        </div>



                                    </div>




                                    <div class="flex space-y-3">
                                        <div class="mb-6 bg-black w-full p-6">
<!--                                            VIDEO HERE-->
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                               for="name"
                                        >
                                            Upload Movie Trailer
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 px-6 pt-4 pb-2 text-dark">
                                            <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max Video Length: <span class="text-orange-400">4 hours</span>
                                                </li>
                                                <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span>
                                                </li>
                                            </ul>

<!--                                            <VideoUpload class="text-black"/>-->

                                        </div>

                                    </div>



                                </div>



                            <!--Right Column-->
                                <div>

                                    <form @submit.prevent="submit">

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="release_date"
                                            >
                                                Release Year
                                            </label>

                                            <input v-model="form.release_year"
                                                   class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                                   type="number"
                                                   name="release_year"
                                                   id="release_year"
                                                   minlength="4"
                                                   maxlength="4"

                                            >
                                            <div v-if="form.errors.release_year" v-text="form.errors.release_year"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="category"
                                            >
                                                Category
                                            </label>

                                            <select class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs "
                                                    v-model="form.category" @change="chooseCategory($event)"
                                            >
                                                <option v-for="category in props.categories"
                                                        :key="category.id" :value="category.id">{{category.name}}</option>


                                            </select>
        <!--    This was for practice... the next step is to loop over the sub-categories that belongTo the category selected. -->
        <!--                                    <select>-->
        <!--                                        <option v-for="option in options" :value="option.value">{{option.text}}</option>-->
        <!--                                    </select>-->
                                            <span class="">{{movieStore.category_description}}</span>
                                            </div>

                                            <div v-if="form.errors.category" v-text="form.errors.category"
                                                 class="text-xs text-red-600 mb-2">
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 text-gray-600 uppercase font-bold text-xs text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="name"
                                            >
                                                Logline
                                            </label>

                                            <input v-model="form.logline"
                                                   class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                                   type="text"
                                                   name="logline"
                                                   id="logline"
                                                   required
                                            >
                                            <div v-if="form.errors.logline" v-text="form.errors.logline"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="file_url"
                                            >
                                                Change Video URL (if hosted externally) <span class="text-white dark:text-black">*</span>
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

                                        <div class="block mb-6 uppercase font-bold text-xs text-light">
                                            <div class="mb-2">* Notes:</div>

                                            <ul class="list-decimal pb-2 ml-2 mb-4 border-b">
                                                <li>
                                                    We have not enabled the use of Facebook videos for security purposes.
                                                </li>
                                            </ul>
                                        </div>


                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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

                                    </form>

                                </div>
                                <!-- End Right Column -->
                            </div>
                            <!-- End grid 2-col -->


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
import {useForm, usePage} from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { useMovieStore } from "@/Stores/MovieStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import TabbableTextarea from "@/Components/TabbableTextarea"
import Message from "@/Components/Modals/Messages"
import ShowEpisodeEditHeader from "@/Components/ShowEpisodes/Edit/ShowEpisodeEditHeader"
import SingleImage from "@/Components/Multimedia/SingleImage"
import ImageUpload from "@/Components/Uploaders/ImageUpload"
import VideoUpload from "@/Components/Uploaders/VideoUpload"

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()
let movieStore = useMovieStore()

let props = defineProps({
    movie: Object,
    video: Object,
    trailer: Object,
    image: Object,
    categories: Object,
    sub_categories: Object,
    movieCategory: String,
    movieCategorySub: String,
})

let form = useForm({
    id: props.movie.id,
    name: props.movie.name,
    release_year: props.movie.release_year,
    category: props.movie.movie_category_id,
    sub_category: props.movie.movie_category_sub_id,
    description: props.movie.description,
    logline: props.movie.logline,
    user_id: props.movie.user_id,
    team_id: props.movie.team_id,
    file_url: props.movie.file_url,
    www_url: props.movie.www_url,
    instagram_name: props.movie.instagram_name,
    telegram_url: props.movie.telegram_url,
    twitter_handle: props.movie.twitter_handle,
})

let reloadImage = () => {
    Inertia.reload({
        only: ['image'],
    });
};

let submit = () => {
    form.put(route('movies.update', props.movie.slug));
}

videoPlayerStore.currentPage = 'movies'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
})

// let category = ref();

function chooseCategory(event) {
movieStore.category_description =  props.categories[event.target.selectedIndex].description;
}
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


// showStore.episodePoster = props.poster;


function muteMainVideo(){
    videoPlayerStore.mute();
}

function back() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

</script>
