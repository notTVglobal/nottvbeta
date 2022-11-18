<template>
    <Head  title="Create Episode"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mt-3 mb-6">
                <div class="text-3xl">Create Episode</div>
                <div>
                    <Link v-if="teamStore.activeShow.slug" :href="route('shows.manage', {show: teamStore.activeShow.slug})"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                    <Link v-if="!teamStore.activeShow.slug" :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                </div>
            </div>

            <div class="bg-orange-700 text-white w-full p-6"><span class="font-semibold">NOTE: </span>
            We are working on an episode poster and video uploader for this page. For the time being, please
                go to the episode EDIT page after you create the episode to upload a video and add a poster.
            </div>

            <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        Show
                    </label>
                    <div>{{props.show.name}}</div>

                    <div v-if="form.errors.show_id" v-text="form.errors.show_id" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
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
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="episode_number"
                    >
                        Episode Number
                    </label>

                    <input v-model="form.episode_number"
                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-1/2 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           type="text"
                           name="episode_number"
                           id="episode_number"
                           required
                    >
                    <div v-if="form.errors.episode_number" v-text="form.errors.episode_number" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
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
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="video_file_url"
                    >
                        Video URL (if hosted externally, must be a url that ends in .mp4)
                    </label>
                    <input v-model="form.video_file_url"
                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           type="text"
                           name="video_file_url"
                           id="video_file_url"
                    >
                    <div v-if="form.errors.video_file_url" v-text="form.errors.video_file_url" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="video_file_embed_code"
                    >
                        Video Embed Code (IFRAME only)
                    </label>
                    <textarea v-model="form.video_file_embed_code"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              type="text"
                              name="video_file_embed_code"
                              id="video_file_embed_code"
                              required
                    ></textarea>
                    <div v-if="form.errors.video_file_embed_code" v-text="form.errors.video_file_embed_code" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="notes"
                    >
                        Notes (Only your team members see these notes, they are not public)
                    </label>
                    <textarea v-model="form.notes"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              type="text"
                              name="notes"
                              id="notes"
                              required
                    ></textarea>
                    <div v-if="form.errors.notes" v-text="form.errors.notes" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="flex justify-between mb-6">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                        :disabled="form.processing"
                    >
                        Submit
                    </button>
                    <div @click="reset" class="text-blue-600 text-sm cursor-pointer">Reset</div>
                </div>

            </form>

            <div class="flex justify-end mt-6">
                <Link :href="`/teams/${props.team.slug}`" class="text-blue-500 ml-2"> {{ props.team.name }} © 2022 </Link>
            </div>

        </div>
    </div>


</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { useForm } from "@inertiajs/inertia-vue3"
import { onMounted } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    user: Object,
    show: Object,
    team: Object,
})

let form = useForm({
    name: '',
    description: '',
    user_id: props.user.id,
    show_id: props.show.id,
    show_slug: props.show.slug,
    episode_number: '',
    video_file_url: '',
    video_file_embed_code: '',
    notes: '',
});

teamStore.setActiveShow(props.show);
teamStore.setActiveTeam(props.team);

function reset() {
    form.reset();
};
//
let submit = () => {
    form.post(route('showEpisodes.store', props.show.slug));
};

// let submit = () => {
//     form.put(route('shows.showEpisodes.store'));
// };

</script>
