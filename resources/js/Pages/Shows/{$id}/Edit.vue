<template>

    <Head :title="`Edit Show: ${props.show.name}`"/>

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <ShowEditHeader :show="props.show" :team="props.team"/>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>

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
                                            Change Show Poster
                                        </label>

                                        <ImageUpload :image="props.image"
                                                     :server="'/showsUploadPoster'"
                                                     :name="'Upload Show Poster'"
                                                     :maxSize="'30MB'"
                                                     :fileTypes="'image/jpg, image/jpeg, image/png'"
                                                     @reloadImage="reloadImage"
                                        />

                                    </div>

                                </div>



                                <!--Right Column-->
                                <div>
<!--                                    <ShowPosterUpload-->
<!--                                        :team="props.show"-->
<!--                                        :images="props.images"-->
<!--                                    />-->
                                    <form @submit.prevent="submit">

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="name"
                                            >
                                                Show Notes (only visible to team members)
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
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="name"
                                            >
                                                Show Name
                                            </label>

                                            <input v-model="form.name"
                                                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
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
                                                   for="category"
                                            >
                                                Category
                                            </label>

                                            <select class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg block my-2 uppercase font-bold text-xs "
                                                    v-model="form.category" @change="chooseCategory($event)"
                                            >

                                                <option v-for="category in props.categories"
                                                        :key="category.id" :value="category.id">{{category.name}}</option>


                                            </select>
                                            <!--    This was for practice... the next step is to loop over the sub-categories that belongTo the category selected. -->
                                            <!--                                    <select>-->
                                            <!--                                        <option v-for="option in options" :value="option.value">{{option.text}}</option>-->
                                            <!--                                    </select>-->
                                            <div v-if="form.errors.category" v-text="form.errors.category"
                                                 class="text-xs text-red-600 mt-1"></div>

                                           <span class="dark:text-gray-50">{{showCategoryDescription}}</span>
                                        </div>



                                        <div class="mb-6">
                                            <label class="block mb-1 text-gray-600 uppercase font-bold text-xs text-light text-gray-600"
                                                   for="sub_category"
                                            >
                                                Sub-category
                                            </label>
                                            <div class="mb-2 text-sm text-orange-600">Sub-categories are coming soon!</div>

                                            <select disabled class="border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs"
                                                    v-model="form.sub_category"
                                            >
                                                <option value="1">Option</option>
                                            </select>
                                            <div v-if="form.errors.sub_category" v-text="form.errors.sub_category"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="description"
                                            >
                                                Description
                                            </label>
                                            <TabbableTextarea v-model="form.description"
                                                              class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                                              name="description"
                                                              id="description"
                                                              rows="10" cols="30"
                                                              required
                                            />
                                            <div v-if="form.errors.description" v-text="form.errors.description"
                                                 class="text-xs text-red-600 mt-1"></div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                                                   for="name"
                                            >
                                                Instagram Handle
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
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
                                            <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
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

                                        <div class="flex justify-end mb-6">
                                            <JetValidationErrors class="mr-4" />
                                             <button
                                                type="submit"
                                                class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
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
import {ref, onBeforeMount, onMounted} from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useUserStore } from "@/Stores/UserStore";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TabbableTextarea from "@/Components/TabbableTextarea"
import ShowEditHeader from "@/Components/Shows/Edit/ShowEditHeader"
import SingleImage from "@/Components/Multimedia/SingleImage";
import Message from "@/Components/Modals/Messages";
import ImageUpload from "@/Components/Uploaders/ImageUpload";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'shows'

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);

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
    user: Object,
    show: Object,
    team: Object,
    poster: String,
    image: Object,
    categories: Object,
    subCategories: Object,
    showCategory: Object,
    message: String,
});

let form = useForm({
    name: props.show.name,
    description: props.show.description,
    category: props.show.show_category_id,
    sub_category: props.show.show_category_sub_id,
    www_url: props.show.www_url,
    instagram_name: props.show.instagram_name,
    telegram_url: props.show.telegram_url,
    twitter_handle: props.show.twitter_handle,
    notes: props.show.notes,
});

let showCategoryDescription = props.showCategory.Description;

function chooseCategory(event) {
    showCategoryDescription = props.categories[event.target.selectedIndex].description;
}

// let getCategory = ref(null);
// onBeforeMount(async () => {
//     getCategory.value = await props.show.category;
// })

let reloadImage = () => {
    Inertia.reload({
        only: ['image'],
    });
};

let submit = () => {
    form.put(route('shows.update', props.show.slug));
};

let showMessage = ref(true);

</script>
