<template>

    <Head :title="`Edit Movie: ${movie.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-dark text-light p-5 mb-10 pt-6">

            <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

            <header>
                <div class="flex justify-between mb-6">
                    <div>
                        <div class="font-bold mb-4 text-red-700">EDIT MOVIE</div>
                        <h1 class="text-3xl">
                            <Link :href="`/movies/${movie.slug}`" class="text-red-700 font-bold uppercase">{{ movie.name }}</Link>
                        </h1>
                    </div>
                  <div class="flex flex-row flex-wrap">
                    <button
                        @click="submit"
                        class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4 mr-2"
                        :disabled="form.processing"
                    >
                      Save
                    </button>
                    <CancelButton />
                  </div>

                </div>
            </header>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="topBannerErrorMessage"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.logline" v-text="form.errors.logline"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.release_year" v-text="form.errors.release_year"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.release_date" v-text="form.errors.release_date"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.creative_commons_id" v-text="form.errors.creative_commons_id"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.copyrightYear" v-text="form.errors.copyrightYear"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.category" v-text="form.errors.category"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.sub_category" v-text="form.errors.sub_category"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.video_url" v-text="form.errors.video_url"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.www_url" v-text="form.errors.www_url"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.instagram_name" v-text="form.errors.instagram_name"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.telegram_url" v-text="form.errors.telegram_url"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.twitter_handle" v-text="form.errors.twitter_handle"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.notes" v-text="form.errors.notes"
                               class="topBannerErrorMessage"></div>
                          <div v-if="form.errors.status" v-text="form.errors.status"
                               class="topBannerErrorMessage"></div>
                            <!--                            <div v-if="form.errors.video_file_embed_code" v-text="form.errors.video_file_embed_code"-->
                            <!--                                 class="topBannerErrorMessage-->


                            <!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">


                                <!--Left Column-->
                                <div>
                                    <div class="flex space-y-3">
                                        <div class="mb-6">
                                            <SingleImage :image="image" :key="image"/>
                                        </div>
                                    </div>

                                    <div class="w-full">

                                        <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                               for="name"
                                        >
                                            Change Movie Poster
                                        </label>

                                        <ImageUpload :image="image"
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
                                            <video v-if="video.upload_status !== 'processing'" :src="video.cdn_endpoint+video.cloud_folder+video.folder+'/'+video.file_name" :type="video.type" controls></video>
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
                                               for="status"
                                        >
                                          Status
                                        </label>

                                        <div class="text-sm mb-2">Set the status to Active to make the movie accessible to the public. Set the status to Creators only to only make it available to creators. All other statuses will only be viewable by you.</div>

                                        <select required class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs "
                                                v-model="form.status"
                                        >
                                          <option v-for="status in statuses"
                                                  :key="status.id" :value="status.id">{{status.name}}</option>


                                        </select>
                                        <div v-if="form.errors.status" v-text="form.errors.status"
                                             class="errorClass"></div>
                                      </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="name"
                                            >
                                                Movie Name
                                            </label>

                                            <input v-model="form.name"
                                                   class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                                                   type="text"
                                                   name="name"
                                                   id="name"
                                                   required
                                            >
                                            <div v-if="form.errors.name" v-text="form.errors.name"
                                                 class="errorClass"></div>
                                        </div>

                                        <div class="mb-6 w-64">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="release_date"
                                            >
                                                Release Year
                                            </label>

                                            <input v-model="selectedReleaseYear" @change="handleReleaseYearChange"

                                                   class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                                                   type="number"
                                                   name="release_year"
                                                   id="release_year"
                                                   min="1900"
                                                   max="2100"

                                            >
                                            <div v-if="form.errors.release_year" v-text="form.errors.release_year"
                                                 class="errorClass"></div>
                                        </div>

                                      <div class="mb-6 w-64">
                                        <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                               for="creative_commons"
                                        >
                                          Creative Commons / Copyright
                                        </label>

                                        <select class="border border-gray-400 text-gray-800 py-2 pl-2 pr-8 w-fit rounded-lg block mb-2 uppercase font-bold text-xs"
                                                v-model="selectedCreativeCommons" @change="handleCreativeCommonsChange">
                                          <option v-for="cc in creative_commons" :key="cc.id" :value="cc.id">{{ cc.name }}</option>
                                        </select>

                                        <div v-if="form.errors.creative_commons_id" v-text="form.errors.creative_commons_id"
                                             class="errorClass"></div>

                                        <div class="">{{ selectedCreativeCommonsDescription }}</div>

                                      </div>

                                      <div v-if="selectedCreativeCommons" class="mb-6 w-64">

                                        <div v-if="selectedCreativeCommons === 8">
                                          <input class="hidden border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                                                 type="hidden"
                                                 v-model="selectedCopyrightYear"
                                                 value="null">
                                        </div>
                                        <div v-else>
                                          <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                 for="copyrightYear"
                                          >
                                            Copyright Year
                                          </label>
                                          <input class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                                                 type="number"
                                                 minlength="4"
                                                 maxlength="4"
                                                 v-model="selectedCopyrightYear">
                                        </div>

                                        <div v-if="form.errors.copyrightYear" v-text="form.errors.copyrightYear"
                                             class="errorClass"></div>
                                      </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="category"
                                            >
                                                Category
                                            </label>

                                            <select class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs"
                                                    v-model="selectedCategoryId" @change="chooseCategory"
                                            >
                                                <option v-for="category in categories"
                                                        :key="category.id" :value="category.id">{{category.name}}</option>
                                            </select>

                                          <div v-if="form.errors.category" v-text="form.errors.category"
                                               class="errorClass"></div>

                                            <span class="">{{movieStore.category_description}}</span>


                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="sub_category"
                                            >
                                                Sub-category
                                            </label>

                                            <select class="border border-gray-400 text-gray-800 disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-1/2 rounded-lg block mb-2 uppercase font-bold text-xs"
                                                    v-model="selectedSubCategoryId" :disabled="!selectedCategoryId"  @change="chooseSubCategory"
                                            >
                                                <option disabled value="">Select a subcategory</option>
                                                <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id">

                                                  {{ subCategory.name }}
                                                </option>
                                            </select>
                                            <span class="">{{movieStore.sub_category_description}}</span>
                                            <div v-if="form.errors.sub_category" v-text="form.errors.sub_category"
                                                 class="errorClass"></div>
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
                                                 class="errorClass"></div>
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
                                                 class="errorClass"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="file_url"
                                            >
                                                Change Video URL (if hosted externally) <span class="text-white dark:text-black">*</span>
                                            </label>

                                            <input v-model="form.video_url"
                                                   class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                   type="text"
                                                   name="video_url"
                                                   id="video_url"
                                            >
                                            <div v-if="form.errors.video_url" v-text="form.errors.video_url"
                                                 class="errorClass"></div>
                                        </div>

                                        <div class="block mb-6 text-sm font-semibold tracking-wider">
                                            <div class="mb-2 uppercase">* Note:</div>

                                            <ul class="list-decimal pb-2 ml-2 mb-4 border-b font-medium">
                                                <li>
                                                  We have <span class="underline">not</span> enabled the use of Facebook or YouTube videos for security purposes.
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
                                                 class="errorClass"></div>
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
                                                 class="errorClass"></div>
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
                                                 class="errorClass"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                                                   for="name"
                                            >
                                                X (formerly Twitter) @
                                            </label>

                                            <input v-model="form.twitter_handle"
                                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                                   type="text"
                                                   name="twitter_handle"
                                                   id="twitter_handle"
                                            >
                                            <div v-if="form.errors.twitter_handle" v-text="form.errors.twitter_handle"
                                                 class="errorClass"></div>
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
import { Inertia } from "@inertiajs/inertia"
import {useForm, usePage} from "@inertiajs/inertia-vue3"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useMovieStore } from "@/Stores/MovieStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import TabbableTextarea from "@/Components/Global/TextEditor/TabbableTextarea"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import ImageUpload from "@/Components/Global/Uploaders/ImageUpload"
import VideoUpload from "@/Components/Global/Uploaders/VideoUpload"
import CancelButton from "@/Components/Global/Buttons/CancelButton.vue"
import { computed, onBeforeUnmount, onMounted, ref, toRefs, watch } from 'vue'

usePageSetup('movieEdit')

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const movieStore = useMovieStore()

let props = defineProps({
    movie: Object,
    video: Object,
    trailer: Object,
    image: Object,
    categories: Object,
    statuses: Object,
    creative_commons: Object,
    can: Object,
})

let selectedCategoryId = ref(props.movie.movie_category_id);
let selectedSubCategoryId = ref(props.movie.movie_category_sub_id);
const selectedCreativeCommons = ref(props.movie.creative_commons_id);
let selectedCopyrightYear = ref(props.movie.copyrightYear);
let selectedReleaseYear = ref(props.movie.release_year);
const currentYear = new Date().getFullYear();

const subCategories = computed(() => {
  const category = props.categories.find(cat => cat.id === selectedCategoryId.value);
  return category ? category.sub_categories : [];
});

const handleCreativeCommonsChange = () => {
  if (selectedCreativeCommons.value === 8) {
    selectedCopyrightYear.value = null;
  } else if (selectedCopyrightYear.value === null) {
    // Pre-populate with current year only if copyrightYear is null
    selectedCopyrightYear.value = currentYear;
  }
};

const selectedCreativeCommonsDescription = computed(() => {
  const selectedCC = props.creative_commons.find((cc) => cc.id === selectedCreativeCommons.value);
  return selectedCC ? selectedCC.description : '';
});

const handleReleaseYearChange = () => {
  if (selectedReleaseYear.value === null) {
    // Pre-populate with current year only if copyrightYear is null
    selectedReleaseYear.value = currentYear;
  }
};

// const releaseYear = computed(() => {
//   return props.movie.release_year === null ? new Date().getFullYear() : props.movie.release_year;
// });

// Initialize releaseYear with either the movie's release_year or the current year
// form.releaseYear = props.movie.release_year ?? new Date().getFullYear();
// Destructure props for easier access
// const { movie, video } = toRefs(props);

// Handling nullable release_year
// const releaseYear = computed(() => movie.value.release_year ?? new Date().getFullYear());


// Watchers to update the store based on category and subcategory selections
watch(selectedCategoryId, () => {
  movieStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value);
}, { immediate: true });

watch(selectedSubCategoryId, () => {
  movieStore.updateSubCategoryDescription(selectedSubCategoryId.value);
});

onMounted(() => {
  movieStore.categories = props.categories;
  movieStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value);
  watch(() => props.movie.creative_commons_id, (newVal) => {
    selectedCreativeCommons.value = newVal;
    handleCreativeCommonsChange();
  });
});

const chooseCategory = () => {
  // Update the selected category ID based on the new selection
  // Vue automatically updates selectedCategoryId due to v-model binding
  // So, there is no need to manually set it here

  // Call the store method to update descriptions and subcategories
  movieStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value);
};

const chooseSubCategory = () => {
  // Update the store state based on the new subcategory selection
  movieStore.updateSubCategoryDescription(selectedSubCategoryId.value);
};



// let subCategory = ref(null);

let form = useForm({
    id: props.movie.id,
    name: props.movie.name,
    status: props.movie.status_id,
    release_year: selectedReleaseYear,
    copyrightYear: selectedCopyrightYear,
    creative_commons_id: selectedCreativeCommons.value,
    category: movieStore.category_id,
    sub_category: movieStore.sub_category_id,
    description: props.movie.description,
    logline: props.movie.logline,
    user_id: props.movie.user_id,
    team_id: props.movie.team_id,
    video_url: props.video.video_url ?? null,
    www_url: props.movie.www_url,
    instagram_name: props.movie.instagram_name,
    telegram_url: props.movie.telegram_url,
    twitter_handle: props.movie.twitter_handle,
})

let submit = () => {
  form.category = movieStore.category_id;
  form.sub_category = movieStore.sub_category_id;
  form.copyrightYear = selectedCopyrightYear;
  form.creative_commons_id = selectedCreativeCommons.value;
  form.release_year = selectedReleaseYear.value;
  form.patch(route('movies.update', props.movie.slug));
}

let reloadImage = () => {
    Inertia.reload({
        only: ['image'],
    });
};



// let category = ref();

// function chooseCategory(event) {
// movieStore.category_description =  props.categories[event.target.selectedIndex].description;
// }


// Computed property to filter subcategories based on the selected category
// const subCategories = computed(() => {
//   const category = props.categories.find(cat => cat.id === selectedCategoryId.value);
//   return category ? category.sub_categories : [];
// });





// Initialize and update descriptions when IDs change
// watch([selectedCategoryId, selectedSubCategoryId], () => {
//   movieStore.initializeDescriptions(props.categories, selectedCategoryId.value, selectedSubCategoryId.value);
//
// }, { immediate: true });

// watch([subCategories, selectedCategoryId, selectedSubCategoryId], () => {
//   movieStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value);
//
// }, { immediate: true });

// Initialize on mount
// onMounted(() => {
//   // movieStore.initializeDescriptions(props.categories, selectedCategoryId.value, selectedSubCategoryId.value);
//   // movieStore.initializeDescriptions(props.categories, props.movie.movie_category_id, props.movie.movie_category_sub_id);
//   movieStore.categories = props.categories
//   movieStore.category_id = props.movie.movie_category_id
//   movieStore.category_description = props.movie.movie_category.description
//   movieStore.sub_category_id = props.movie.movie_category_sub_id
//   movieStore.sub_category_description = props.movie.movie_category_sub.description
//
//   // movieStore.initializeDescriptions()
// });

// // Initialize category and subcategory descriptions
// const initializeDescriptions = () => {
//   const category = props.categories.find(cat => cat.id === selectedCategoryId.value);
//   movieStore.category_id = category ? category.id : '';
//   movieStore.category_description = category ? category.description : '';
//   movieStore.sub_categories = category ? category.sub_categories : [];
//
//   subCategory = subCategories.value.find(sub => sub.id === selectedSubCategoryId.value);
//   movieStore.sub_category_id = subCategory ? subCategory.id : '';
//   movieStore.sub_category_description = subCategory ? subCategory.description : '';
//   console.log('GARCK' + selectedSubCategoryId.value)
//   movieStore.sub_category_id = selectedSubCategoryId.value;
// };
//
// // Call the function to set initial descriptions
// initializeDescriptions();
//
// // When a category is chosen
// const chooseCategory = () => {
//   selectedCategoryId.value = parseInt(event.target.value);
//   initializeDescriptions(); // Re-initialize to update subcategories and descriptions
// };
//
// // When a subcategory is chosen
// const chooseSubCategory = () => {
//   subCategory = movieStore.sub_categories.find(sub => sub.id === selectedSubCategoryId.value);
//   console.log('FFFFFFAAAAAA' + selectedSubCategoryId.value)
//   movieStore.sub_category_id = subCategory ? subCategory.id : '';
//   movieStore.sub_category_description = subCategory ? subCategory.description : '';
// };

// Watchers to update descriptions when IDs change
// watch(selectedCategoryId, () => {
//   const category = props.categories.find(cat => cat.id === selectedCategoryId.value);
//   movieStore.category_description = category ? category.description : '';
//   selectedSubCategoryId.value = null; // Reset subcategory selection
// });
//
// watch(selectedSubCategoryId, () => {
//   console.log('GET FLIGHTY' + selectedSubCategoryId.value)
//   const subCategory = movieStore.sub_categories.find(sub => sub.id === selectedSubCategoryId.value);
//   movieStore.sub_category_id = subCategory ? subCategory.id : '';
//
//   movieStore.sub_category_description = subCategory ? subCategory.description : '';
// });


// const chooseCategory = (event) => {
//   selectedCategoryId.value = parseInt(event.target.value);
// };
//
// const chooseSubCategory = (subCategoryId) => {
//   selectedSubCategoryId.value = subCategoryId;
// };

function muteMainVideo(){
    videoPlayerStore.mute();
}

function back() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

onBeforeUnmount(() => {
  movieStore.reset(); // Call the reset method of your store
});

</script>
<style scoped>
.topBannerErrorMessage {
  background-color: black; /* bg-black */
  padding: 0.5rem; /* p-2 */
  width: 100%; /* w-full */
  color: white; /* text-white */
  font-weight: 600; /* font-semibold, for bold you might want to use 700 */
  font-size: 1rem; /* Adjust based on your design, as Tailwind's text sizes are utility-based */
}
.errorClass {
  color: lightgreen;
  font-weight: 600; /* font-semibold, for bold you might want to use 700 */
}
</style>