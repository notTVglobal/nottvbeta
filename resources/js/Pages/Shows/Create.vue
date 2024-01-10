<template>
    <Head title="Create Show"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <div class="flex justify-between mt-3 mb-6">
                <div class="text-3xl">Create Show</div>
                <div>
                    <CancelButton />
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
                                :class="'status-' + team.status.id"
                            >
                                {{ team.name }} ({{ team.status.status }})
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

            <CheckboxNotification />

    </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import {useForm, usePage} from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore.js";
import { useNotificationStore } from "@/Stores/NotificationStore.js";
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import {Inertia} from "@inertiajs/inertia";
import CancelButton from "@/Components/Buttons/CancelButton.vue";
import CheckboxNotification from "@/Components/Modals/CheckboxNotification.vue";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let userStore = useUserStore()
let notificationStore = useNotificationStore()

let props = defineProps({
    teams: Object,
    userId: Number,
    categories: Object,
    subCategories: Object,
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

let showCategoryDescription = ref(null);

userStore.currentPage = 'shows'
userStore.showFlashMessage = true;
form.team_id = teamStore.id;

const checkForTeams = () => {
    if (props.teams.data.length === 0) {
        // Perform some actions if data array is empty
        console.log('No teams available.');
        notificationStore.active = true;
        notificationStore.title = "No teams available.";
        notificationStore.body = "Please create a team before you create a show.";
        notificationStore.buttonLabel = "OKAY";
        notificationStore.onClickAction = "redirect";
        notificationStore.uri = "/shows/create";
        notificationStore.redirectPageUri = "/teams/create";
        // Additional logic for empty array
    } else {
        // Do nothing if data array is not empty
        console.log('Teams are available.');
    }
};

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    checkForTeams();
});

let submit = () => {
    form.post('/shows');
}

function chooseCategory(event) {
    showCategoryDescription = props.categories[event.target.selectedIndex].description;
}

function reset() {
    form.reset();
}

function back() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

</script>

<style scoped>
.status-1 {
    color: green; /* Example color for status ID 1 */
}
.status-2 {
    color: blue; /* Example color for status ID 2 */
}
.status-3 {
    color: purple; /* Example color for status ID 3 */
}
.status-4 {
    color: orange; /* Example color for status ID 4 */
}
.status-5 {
    color: red; /* Example color for status ID 4 */
}
.status-6 {
    color: darkgray; /* Example color for status ID 4 */
    font-style: italic;
}
.status-7 {
    color: black; /* Example color for status ID 4 */
    font-style: italic;
}
.status-8 {
    color: black; /* Example color for status ID 4 */
    font-style: italic;
}
.status-9 {
    color: red; /* Example color for status ID 4 */
    font-style: italic;
}
.status-10 {
    color: red; /* Example color for status ID 4 */
    font-style: italic;
}
.status-11 {
    color: darkgray; /* Example color for status ID 4 */
}
</style>
