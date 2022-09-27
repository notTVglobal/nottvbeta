<template>

    <Head :title="Edit" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <h1 class="text-3xl"><Link :href="`/teams/${props.team.id}`" class="text-indigo-600">{{props.team.name}}</Link> > <span class="font-semibold">Edit</span></h1>
                <span class="text-xs font-semibold text-red-700">Edit Mode</span>
                <div>
                    <Link :href="`/teams/${props.team.id}`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                </div>
            </div>

            <div class="max-w-lg mx-auto mt-8">
                <div class="mb-6"><span class="text-xs">Team ID: </span><span class="font-semibold">{{props.team.id}}</span></div>
                <div class="mb-6"><img :src="props.team.team_poster_url" /></div>
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="name"
                        >
                            Team Name
                        </label>

                        <input v-model="form.name"
                               class="border border-gray-400 p-2 w-full rounded-lg"
                               type="text"
                               name="name"
                               id="name"
                               required
                        >
                        <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="description"
                        >
                            Description
                        </label>
                        <TabbableTextarea v-model="form.description"
                               class="border border-gray-400 p-2 w-full rounded-lg"
                               name="description"
                               id="description"
                               rows="10" cols="30"
                               required
                        />
                        <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="description"
                        >
                            Maximum # of Team Members
                        </label>
                        <input v-model="form.totalSpots"
                                          class="border border-gray-400 p-2 w-full rounded-lg"
                                          type="text"
                                          name="totalSpots"
                                          id="totalSpots"
                        />
                        <div v-if="form.errors.totalSpots" v-text="form.errors.totalSpots" class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="flex justify-between mb-6">
                        <button
                            type="submit"
                            class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
                            :disabled="form.processing"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import TabbableTextarea from "@/Components/TabbableTextarea"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    team: Object
});

let title = "Edit > " + props.team.name;

let form = useForm({
    id: props.team.id,
    name: props.team.name,
    description: props.team.description,
    totalSpots: props.team.totalSpots,
    logo: props.team.logo,
});

let submit = () => {
    form.put(route('teams.update', props.team.id));
};

// let submit = () => {
//     form.put('/teams');
// };
</script>


