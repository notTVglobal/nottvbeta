<template>

    <Head :title="`Manage Episode: ${props.episode.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-dark rounded text-light p-5">

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                                <span class="font-medium">
                                    {{ props.message }}
                                </span>
            </div>

            <header>
                <div class="flex justify-between mb-3">
                    <div class="gap-2">
                        <div class="font-bold mb-4 text-orange-400">MANAGE EPISODE</div>
                        <div>
                            <EpisodeHeader
                                :episode="props.episode"
                                :show="props.show"
                                :team="props.team"
                            />
                        </div>
                    </div>


                    <div>
                        <div class="flex flex-wrap-reverse justify-end gap-2">

                            <div class="">
                                <Link
                                    :href="`/shows/${show.slug}/episode/${episode.slug}/upload`"
                                    v-if="!episode.video_file_url">
                                    <button
                                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
                                    >Upload
                                    </button>
                                </Link>
                            </div>

                            <div class="" v-if="teamStore.can.goLive && !episode.video_file_url">
                                    <button
                                        @click="showGoLive"
                                        class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                                    >Go Live
                                    </button>
                            </div>
                            <div class="">
                                <Link
                                    :href="`/shows/${show.slug}/episode/${episode.slug}/edit`"
                                    v-if="teamStore.can.editEpisode">
                                    <button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    >Edit
                                    </button>
                                </Link>
                            </div>
                            <div>
                                <Link :href="`/dashboard`">
                                    <button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    >Dashboard
                                    </button>
                                </Link>
                            </div>
                        </div>


                        <div class="flex justify-end mt-6">
                            <div class="flex flex-col">
                                <div><span class="text-xs capitalize font-semibold">Show: </span>
                                    <Link :href="`/shows/${show.slug}/manage`" class="text-blue-500 ml-2"> {{
                                            show.name
                                        }}
                                    </Link>
                                </div>
                                <div><span class="text-xs capitalize font-semibold mr-2">Show Runner: </span> {{
                                        show.showRunner
                                    }}
                                </div>
                                <div><span class="text-xs capitalize font-semibold mr-2">Episode Number: </span>
                                    <span v-if="episode.episode_number">{{ episode.episode_number }}</span>
                                    <span v-if="!episode.episode_number">{{ episode.id }}</span>
                                </div>
                                <div><span class="text-xs capitalize font-semibold mr-2">
                                    {{ formatDate(props.episode.created_at) }}
                                </span></div>

                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <div v-if="!showGoLive">
                <div class="text-sm font-semibold uppercase mb-2">Go Live Instructions</div>
                Put a preview window here. Display the RTMP Url with streamkey using episode UUID: rtmp://mist.nottv.io/live/episodes+UUID and fix the "golive" button showing this div
            </div>

            <div class="my-6 ml-10 w-3/4">
                <div class="text-sm font-semibold uppercase mb-2">Episode Description</div>
                {{ episode.description }}
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div
                            class="flex justify-center shadow overflow-hidden border-b border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">


                            <img v-if="!episode.video_file_url && !episode.video_file_embed_code" :src="`/storage/images/EBU_Colorbars.svg.png`" class="max-h-32">
                            <iframe v-if="episode.video_file_url && !episode.video_file_embed_code"
                                    class="rumble" width="640" height="360" :src="`${episode.video_file_url}`" frameborder="0" allowfullscreen>
                            </iframe>
                            <div v-if="!episode.video_file_url && episode.video_file_embed_code" v-html="videoEmbedCode">
                            </div>
                            <div v-if="episode.video_file_url && episode.video_file_embed_code" v-html="videoEmbedCode">
                            </div>
                        </div>

                    </div>

                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-5">

                        <div class="my-6 ml-10 md:w-3/4">
                            <div class="text-sm font-semibold uppercase mb-2">Episode Notes (only team members see
                                these)
                            </div>
                            {{ episode.notes }}
                        </div>

                    </div>

                </div>

                <EpisodeFooter :team="props.team"/>
            </div>
        </div>
    </div>

</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { ref, onMounted } from "vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
import {useTeamStore} from "@/Stores/TeamStore.js"
import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter";
import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader";
// import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader"
// import Episode from "@/Components/ShowEpisodes/Episode"
// import EpisodeCreditsList from "@/Components/ShowEpisodes/EpisodeCreditsList";
// import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter"

let videoPlayerStore = useVideoPlayerStore()
let showStore = useShowStore();
let teamStore = useTeamStore();

videoPlayerStore.currentPage = 'episodes'

onMounted(async () => {
    videoPlayerStore.makeVideoTopRight();

});

let props = defineProps({
    show: Object,
    team: Object,
    episode: Object,
    message: String,
    can: Object,
});

teamStore.can = props.can;

let videoEmbedCode = props.episode.video_file_embed_code;

let showGoLive = false

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));


</script>


