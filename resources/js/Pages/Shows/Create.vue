<template>
    <Head title="Create Show"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex justify-between mt-3 mb-6">
                <div class="text-3xl">Create Show</div>
                <div>
                    <button
                        @click="back"
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel
                    </button>
                </div>
            </div>

            <div class="bg-orange-700 text-white w-full p-6"><span class="font-bold">NOTE: </span>
                We are working on an episode poster and video uploader for this page. For the time being, please
                go to the show <span class="font-bold">EDIT</span> page after you create the show to add a video and a poster.
            </div>

            <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                               for="name"
                        >
                            Team
                        </label>
                        <select class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800"
                                v-model="form.team_id"
                                required

                        >
                            <option
                                v-for="team in props.teams.data"
                                :key="team.id"
                                :value="team.id"
                                class="bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"

                            >
                                {{ team.name }}
                            </option>

                        </select>


                        <div v-if="form.errors.team_id" v-text="form.errors.team_id" class="text-xs text-red-600 mt-1"></div>
                    </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="name"
                    >
                        Show Name
                    </label>

                    <input v-model="form.name"
                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           type="text"
                           name="name"
                           id="name"
                           required
                    >
                    <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="category"
                    >
                        Category
                    </label>


                    <select class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs "
                            v-model="form.category" @change="chooseCategory($event)"
                    >
                        <option v-for="category in props.categories"
                                :key="category.id" :value="category.id">{{category.name}}</option>


                    </select>
                    <div v-if="form.errors.category" v-text="form.errors.category"
                         class="text-xs text-red-600 mt-1"></div>

                    {{showCategoryDescription}}
                </div>

                <div class="mb-6">
                    <label class="block mb-1 uppercase font-bold text-xs dark:text-gray-200"
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
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="description"
                    >
                        Description
                    </label>
                    <textarea v-model="form.description"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              type="text"
                              name="description"
                              id="description"
                              required
                    ></textarea>
                    <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
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
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
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
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
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
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
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

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="notes"
                    >
                        Notes (Only your team members see these notes, they are not public)
                    </label>
                    <textarea v-model="form.notes"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              type="text"
                              name="notes"
                              id="notes"
                    ></textarea>
                    <div v-if="form.errors.notes" v-text="form.errors.notes" class="text-xs text-red-600 mt-1"></div>
                </div>

                <input v-model="form.user_id" hidden>
                <div class="flex justify-between mb-6">
                    <JetValidationErrors class="mr-4" />
                    <button
                        type="submit"
                        class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                        :disabled="form.processing"
                    >
                        Submit
                    </button>
                </div>

            </form>

    </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'shows'

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
    teams: Object,
    userId: Number,
    categories: Object,
    subCategories: Object,
    message:String,
})

let form = useForm({
    name: '',
    description: '',
    user_id: props.userId,
    team_id: '',
    category: '',
    sub_category: '',
    www_url: '',
    instagram_name: '',
    telegram_url: '',
    twitter_handle: '',
    notes: '',
});

form.team_id = teamStore.id;

let showCategoryDescription = ref(null);
function chooseCategory(event) {
    showCategoryDescription = props.categories[event.target.selectedIndex].description;
}

let submit = () => {
    form.post('/shows');
}

function reset() {
    form.reset();
}

let showMessage = ref(true);

function back() {
    window.history.back()
}

</script>
