<template>
    <Head  title="Create Episode"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

<!--            <Message v-if="userStore.showFlashMessage && $page.props.flash" :flash="$page.props.flash"/>-->

            <div class="flex justify-between mt-3 mb-6">
                <div class="text-3xl">Create Episode</div>
                <div>

                </div>
            </div>

            <div class="mx-4">
                <div class="alert alert-info mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><span class="font-semibold">NOTE: </span>
                    We are working on an episode poster and video uploader for this page. For the time being, please
                    go to the episode EDIT page after you create the episode to add a video and a poster.</span>
                </div>
            </div>

            <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="notes"
                    >
                        Notes <br>(Only your team members see these notes, they are not public)
                    </label>
                    <textarea v-model="form.notes"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                              type="text"
                              name="notes"
                              id="notes"
                    ></textarea>
                    <div v-if="form.errors.notes" v-text="form.errors.notes" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="showName"
                    >
                        Show Name
                    </label>
                    <div class="font-bold">{{props.show.name}}</div>

                    <div v-if="form.errors.show_id" v-text="form.errors.show_id" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="showCategory"
                    >
                        Category
                    </label>
                    <div class="font-bold">{{props.show.showCategoryName}}</div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="showCategory"
                    >
                        Sub-category
                    </label>
                    <div class="mb-2 text-sm text-orange-600">Sub-categories are coming soon!</div>
                    <div class="font-bold">{{props.show.subCategoryName}}</div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="name"
                    >
                        Episode Name
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
                           for="episode_number"
                    >
                        Episode Number
                    </label>

                    <input v-model="form.episode_number"
                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-1/2 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           type="text"
                           name="episode_number"
                           id="episode_number"
                    >
                    <div v-if="form.errors.episode_number" v-text="form.errors.episode_number" class="text-xs text-red-600 mt-1"></div>
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

<!--                <div class="mb-6">-->
<!--                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"-->
<!--                           for="youtube_url"-->
<!--                    >-->
<!--                        YouTube URL-->
<!--                    </label>-->
<!--                    <input v-model="form.youtube_url"-->
<!--                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"-->
<!--                           type="text"-->
<!--                           name="youtube_url"-->
<!--                           id="youtube_url"-->
<!--                    >-->
<!--                    <div v-if="form.errors.youtube_url" v-text="form.errors.youtube_url" class="text-xs text-red-600 mt-1"></div>-->
<!--                </div>-->

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="video_url"
                    >
                        Video URL (External MP4 only)
                    </label>
                    <input v-model="form.video_url"
                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           type="text"
                           name="video_url"
                           id="video_url"
                    >
                    <div class="text-xs mt-1">
                        Example: <span class="underline">https://domainname.com/filename.mp4</span>
                    </div>
                    <div v-if="form.errors.video_url" v-text="form.errors.video_url" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                           for="video_embed_code"
                    >
                        Embed Code (Rumble or Bitchute only) <span class="text-white">*</span>
                    </label>
                    <textarea v-model="form.video_embed_code"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              type="text"
                              name="video_embed_code"
                              id="video_embed_code"
                    ></textarea>
                    <div v-if="form.errors.video_embed_code" v-text="form.errors.video_embed_code" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mt-2 mb-6 pb-4 border-b">
                    <div class="mb-2 block uppercase font-bold text-xs">
                        * Notes about video embedding:
                    </div>
                    <ul class="list-decimal pb-2 ml-2">
                        <li>
                            If both URL and Embed Code are provided the system will attempt to get the Video Url from the Embed Code.
                        </li>
                        <li>
                            We have <span class="font-bold">not</span> enabled the use of Facebook videos for security purposes.
                        </li>
                        <li>
                            If you want to use YouTube, enter the YouTube video URL above in the YouTube URL field. This option is least preferrable, due to a lower quality user experience.
                        </li>
                    </ul>
                </div>

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

            <div class="flex justify-end mt-6">
                <Link :href="`/teams/${props.team.slug}`" class="text-blue-500 ml-2"> {{ props.team.name }} © 2022 </Link>
            </div>

        </div>
    </div>


</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import {useForm, usePage} from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import {Inertia} from "@inertiajs/inertia";
import CancelButton from "@/Components/Buttons/CancelButton.vue";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let userStore = useUserStore()

let props = defineProps({
    user: Object,
    show: Object,
    team: Object,
})

// userStore.currentPage = 'episodes'
// userStore.showFlashMessage = true;

// if (props.show) {
//     teamStore.setActiveShow(props.show);
// }
//
// if (props.team) {
//     teamStore.setActiveTeam(props.team);
// }

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

let form = useForm({
    name: '',
    description: '',
    user_id: props.user.id,
    show_id: props.show.id,
    show_slug: props.show.slug,
    episode_number: '',
    video_url: '',
    youtube_url: '',
    video_embed_code: '',
    notes: '',
});

function reset() {
    form.reset();
}

function addEmbedCodeConfirm() {
    if (confirm("Are you sure you want to add this embed code? It will override the video url.")) {
        form.post(route('showEpisodes.store', props.show.slug));
    }
}

let submit = () => {
    if(form.video_embed_code && form.video_url) {
        addEmbedCodeConfirm();
    } else
    form.post(route('showEpisodes.store', props.show.slug));
};

// let submit = () => {
//     form.put(route('shows.showEpisodes.store'));
// };

// let showMessage = ref(true);

// function back() {
//     let urlPrev = usePage().props.value.urlPrev
//     if (urlPrev !== 'empty') {
//         Inertia.visit(urlPrev)
//     }
//     if (urlPrev === 'empty') {
//         Inertia.visit('/shows/'+props.show.slug+'/manage')
//     }
// }

</script>
