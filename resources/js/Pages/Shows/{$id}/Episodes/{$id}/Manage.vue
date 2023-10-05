<template>

    <Head :title="`Manage Episode: ${props.episode.name}`"/>



    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white rounded text-black p-5"
             :class="goLive">

            <div class="font-bold text-xl p-4 my-4 w-full text-center"
                :class="goLiveBanner">
                <div v-if="!teamStore.goLiveDisplay">
                    <div v-if="props.episode.show_episode_status_id < 6">Create your episode.</div>
                    <div><span class="uppercase text-xs">Status:</span> <span :class="episodeStatusClass">{{ episodeStatus.name }}</span></div>
                </div>
                <div v-else>
                    <span>You are about to go live.</span>
<!--                    if the episode is scheduled to go live then display the next line instead -->
<!--                    <span v-if="episode.scheduled_release > now">You are scheduled to go live.</span>-->
<!--                    <span v-if="episode.isLive">You are now live.</span>-->

                </div>
            </div>

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

<!--            <div-->
<!--                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"-->
<!--                role="alert"-->
<!--                v-if="props.message"-->
<!--            >-->
<!--                                <span class="font-medium">-->
<!--                                    {{ props.message }}-->
<!--                                </span>-->
<!--            </div>-->

            <header>
                <div class="flex justify-between my-3">
                    <div class="gap-2">
                        <div class="font-bold mb-4 text-orange-400">MANAGE EPISODE</div>
                    </div>
                    <div class="flex flex-wrap-reverse justify-end gap-2">

                        <div class="">
                            <Link
                                :href="`/shows/${show.slug}/episode/${episode.slug}/upload`"
                                v-if="!episode.video_file_url">
                                <button
                                    :disabled="teamStore.goLiveDisplay"
                                    class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
                                >Upload
                                </button>
                            </Link>
                        </div>

                        <div class="" v-if="teamStore.can.goLive && !episode.video_file_url">
                            <button
                                v-if="!teamStore.goLiveDisplay"
                                @click="teamStore.toggleGoLiveDisplay()"
                                :disabled="props.episode.show_episode_status_id > 6"
                                class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                            >Go Live
                            </button>
                            <button
                                v-if="teamStore.goLiveDisplay"
                                @click="teamStore.toggleGoLiveDisplay()"
                                class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                            >Cancel
                            </button>
                        </div>
                        <div class="">
                            <Link
                                :href="`/shows/${show.slug}/episode/${episode.slug}/edit`"

                                v-if="teamStore.can.editEpisode">
                                <button
                                    :disabled="teamStore.goLiveDisplay"
                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg disabled:bg-gray-400"
                                >Edit
                                </button>
                            </Link>
                        </div>
                        <div>
                            <Link :href="`/shows/${show.slug}/manage`">
                                <button
                                    :disabled="teamStore.goLiveDisplay"
                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg disabled:bg-gray-400"
                                >Manage Show
                                </button>
                            </Link>
                        </div>
                    </div>

                </div>




                <div class="flex justify-between flex-wrap">
                    <div>
                        <EpisodeHeader
                            :episode="props.episode"
                            :show="props.show"
                            :team="props.team"
                        />
                    </div>

                    <div class="flex justify-end mt-6">
                        <div class="flex flex-col">
                            <div class="text-center mb-3 pr-3">
                                <button v-if="props.episode.show_episode_status_id === 5" onclick="scheduleReleaseNotice.showModal()" class="bg-green-600 hover:bg-green-500 text-white rounded-lg font-semibold px-4 py-2 mb-4 mr-2">Schedule Release</button><br />
                                <span v-if="props.episode.show_episode_status_id === 6" class="text-xs capitalize font-semibold">Scheduled Release</span><br />
                                <span v-if="props.episode.show_episode_status_id === 6" class="capitalize font-semibold">{{timeAgo}}</span>
                            </div>
                            <div><span class="text-xs capitalize font-semibold">Show: </span>
                                <Link :href="`/shows/${props.show.slug}/manage`" class="text-blue-500 ml-2 uppercase"> {{
                                        props.show.name
                                    }}
                                </Link>
                            </div>
                            <div><span class="text-xs capitalize font-semibold mr-2">Show Runner: </span> {{
                                    props.show.showRunner
                                }}
                            </div>
                            <div><span class="text-xs capitalize font-semibold mr-2">Episode Number: </span>
                                <span v-if="episode.episode_number">{{ episode.episode_number }}</span>
                                <span v-if="!episode.episode_number">{{ episode.id }}</span>
                            </div>
                            <div v-if="releaseDateTime"><span class="text-xs capitalize font-semibold mr-2">
                                    {{ formatDate(releaseDateTime) }}
                                </span></div>

                        </div>
                    </div>

                </div>


            </header>

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-5">

                <div class="my-6 ml-10 md:w-3/4">
                    <div class="mb-2"><span class="text-sm font-semibold uppercase">Episode Notes</span>
                        <span class="tooltip" data-tip="Only team members see these">
                            <font-awesome-icon icon="fa-circle-info" class="ml-1"/>
                        </span>
                    </div>
                    {{ episode.notes }}
                </div>

            </div>

            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-5">

                <div class="flex flex-row flex-wrap my-6 ml-10 md:w-3/4 space-x-2 space-y-2">
                    <div></div>
                    <button onclick="teleprompterNotice.showModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2">Open Teleprompter</button>
                    <button onclick="vtrNotice.showModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2">Open VTR</button>
                    <button onclick="graphicsNotice.showModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2">Open Graphics</button>
                </div>
                <div class="flex flex-row flex-wrap my-6 ml-10 md:w-3/4 space-x-2 space-y-2">
                    <div></div>
                    <button onclick="addBonusContentNotice.showModal()" class="bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-lg px-4 py-2">Add Bonus Content</button>
                    <button onclick="editDescriptionNotice.showModal()" class="bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg px-4 py-2">Edit Episode Description</button>
                </div>

            </div>

            <PopUpModal :id="`teleprompterNotice`">
                <template v-slot:header>TELEPROMPTER</template>
                <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
            </PopUpModal>

            <PopUpModal :id="`vtrNotice`">
                <template v-slot:header>VTR</template>
                <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
            </PopUpModal>

            <PopUpModal :id="`graphicsNotice`">
                <template v-slot:header>Graphics (like Chryon)</template>
                <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
            </PopUpModal>

            <PopUpModal :id="`scheduleReleaseNotice`">
                <template v-slot:header>Schedule Release</template>
                <template v-slot:main><span class="text-orange-500">This button is in development. You can schedule the release from the Show Manage page.</span></template>
            </PopUpModal>

            <PopUpModal :id="`addBonusContentNotice`">
                <template v-slot:header>Add Bonus Content</template>
                <template v-slot:main><span class="text-orange-500">This feature is coming soon.</span></template>
            </PopUpModal>

            <PopUpModal :id="`editDescriptionNotice`">
                <template v-slot:header>Edit Episode Description</template>
                <template v-slot:main><span class="text-orange-500">This button is in development. You can edit the description on the episode Edit page.</span></template>
            </PopUpModal>

            <div v-if="teamStore.goLiveDisplay" class="mx-4 mt-4 mb-2 px-6 py-1 ">
                <div class="text-sm font-semibold bg-red-600 text-white text-center w-full border-2 border-red-600 rounded uppercase px-6 py-1 ">Go Live Instructions</div>
                <div class="shadow bg-red-100 overflow-hidden border-2 border-red-600 rounded p-6">
                    <div>RTMP full url: <span class="font-bold">rtmp://streams.not.tv/live/{YOUR STREAM KEY}</span></div>
                    <div>RTMP url: <span class="font-bold">rtmp://streams.not.tv/live/</span></div>
                    <div>RTMP stream key: <span class="font-bold">{YOUR STREAM KEY}</span></div>
                    <div>RTSP: <span class="font-bold">rtsp://streams.not.tv:5554/{YOUR STREAM KEY}</span></div>
                    <div>
                        <span class="italic">Your stream key is using showName+UUID.</span>
                    </div>
                    <div class="flex flex-row flex-wrap space-x-2 space-y-2 w-2/3 justify-between pt-10 mx-auto">
                        <div></div>
                        <div class="flex flex-col justify-center text-center space-y-2">
                            <div v-if="props.episode.show_episode_status_id === 6">
                                <div>Countdown to scheduled live:</div>
                                <div>{{timeAgo}}</div>
                            </div>
                            <button class="bg-red-600 hover:bg-red-500 text-white font-semibold rounded-lg px-4 py-2">Go Live Now</button>
                        </div>
                        <div class="flex flex-col justify-center">
                            <div>VIDEO STREAM</div>
                            <div class="bg-black px-10 py-10">
                                <video-player-aux />
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div v-if="teamStore.goLiveDisplay" class="mx-4 mt-4 mb-2 px-6 py-1 ">
                <div class="text-sm font-semibold bg-orange-600 text-white text-center w-full border-2 border-orange-600 rounded uppercase px-6 py-1 ">Commercial Breaks</div>
                <div class="shadow bg-orange-100 overflow-hidden border-2 border-orange-600 rounded p-6 space-y-3">
                    <div></div>
                    <div>Click the <span class="font-bold">Trigger Commercial Break</span> button below to go to commercial.</div>
                    <div>You will see a countdown timer until the show resumes. This will be 1-2 minutes.</div>
                    <div>As a notTV creator, your voice matters. How can this feature be improved to better serve you and your sponsors?
                        Please email and let us know: <a href="mailto:hello@not.tv" target="_blank" class="text-blue-500 hover:text-blue-600">hello@not.tv</a>
                    </div>
                    <div class="w-full flex justify-center pt-4">
                        <button class="bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-lg px-4 py-2">Trigger Commercial Break</button>
                    </div>
                </div>

            </div>

<!--            <div class="my-6 ml-10 w-3/4">-->
<!--                <div class="text-sm font-semibold uppercase mb-2">Episode Description</div>-->
<!--                {{ episode.description }}-->
<!--            </div>-->


<!--            <div class="flex flex-col">-->
<!--                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">-->
<!--                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">-->

<!--                        <div-->
<!--                            class="flex justify-center shadow overflow-hidden border-b border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">-->


<!--                            <img v-if="!episode.video_file_url && !episode.video_file_embed_code" :src="`/storage/images/EBU_Colorbars.svg.png`" class="max-h-32">-->
<!--                            <iframe v-if="episode.video_file_url && !episode.video_file_embed_code"-->
<!--                                    class="rumble" width="640" height="360" :src="`${episode.video_file_url}`" frameborder="0" allowfullscreen>-->
<!--                            </iframe>-->
<!--                            <div v-if="!episode.video_file_url && episode.video_file_embed_code" v-html="videoEmbedCode">-->
<!--                            </div>-->
<!--                            <div v-if="episode.video_file_url && episode.video_file_embed_code" v-html="videoEmbedCode">-->
<!--                            </div>-->
<!--                        </div>-->

<!--                    </div>-->



            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg p-5">

                <div class="my-6 ml-10 md:w-3/4">
                    <div class="text-sm font-semibold uppercase mb-2">Episode Rundown</div>
                    <EpisodeRundown />
                </div>

            </div>

<!--                </div>-->

                <EpisodeFooter :team="props.team" :episode="props.episode" :show="props.show"/>
<!--            </div>-->
        </div>
    </div>

</template>

<script setup>
import {computed, onBeforeMount, onMounted, ref} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter";
import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader";
import VideoPlayerAux from "@/Components/VideoPlayer/VideoPlayerAux.vue";
// import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader"
// import Episode from "@/Components/ShowEpisodes/Episode"
// import EpisodeCreditsList from "@/Components/ShowEpisodes/EpisodeCreditsList";
// import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter"
import Message from "@/Components/Modals/Messages";
import EpisodeRundown from "@/Components/ShowEpisodes/Manage/EpisodeRundown.vue";
import PopUpModal from "@/Components/Modals/PopUpModal.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import { useTimeAgo } from '@vueuse/core'

let videoPlayerStore = useVideoPlayerStore()
let showStore = useShowStore();
let teamStore = useTeamStore();
let userStore = useUserStore()

userStore.currentPage = 'episodes'
userStore.showFlashMessage = true;

let playerName = 'aux-player';

onMounted(async () => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

});

let props = defineProps({
    show: Object,
    team: Object,
    episode: Object,
    episodeStatus: Object,
    releaseDateTime: String,
    scheduledDateTime: String,
    can: Object,
});

// const timeAgo = useTimeAgo(new Date(2023, 10, 5))
const timeAgo = useTimeAgo(props.scheduledDateTime)

teamStore.can = props.can;

let videoEmbedCode = props.episode.video_file_embed_code;

let showGoLive = ref(false)

const goLive = computed(() => ({
        'border-4 border-red-500': teamStore.goLiveDisplay
}))

const goLiveBanner = computed(() => ({
    'bg-red-500 text-white': teamStore.goLiveDisplay,
    'bg-black text-red-600': !teamStore.goLiveDisplay
}))

const episodeStatusClass = computed(() => ({
    'font-semibold text-xl text-orange-400': props.episodeStatus.id===1,
    'text-xl text-green-400': props.episodeStatus.id===2,
    'font-semibold text-xl text-green-600': props.episodeStatus.id===3,
    'font-bold text-xl text-green-600': props.episodeStatus.id===4,
    'font-semibold text-xl text-purple-700': props.episodeStatus.id===5,
    'font-italic text-xl text-pink-500': props.episodeStatus.id===6,
    'font-bold text-xl light:text-black dark:text-white': props.episodeStatus.id===7,
    'font-medium text-xl text-gray-500': props.episodeStatus.id===8,
    'font-semibold font-italic text-xl text-red-700': props.episodeStatus.id===9,
    'font-bold text-xl text-red-800': props.episodeStatus.id===10,
}))

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

</script>

<style scoped>

.episodeManageBanner {
    @apply bg-black text-red-600;
}

</style>


