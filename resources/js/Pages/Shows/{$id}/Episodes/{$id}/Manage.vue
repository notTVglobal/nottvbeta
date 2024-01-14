<template>

    <Head :title="`Manage Episode: ${props.episode.name}`"/>

    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white rounded text-black p-5"
             :class="goLive">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <ShowEpisodeManageNoticeModals />

            <ShowEpisodeManageHeader
                :episode="episode"
                :episodeStatus="episodeStatus"
                :show="show"
                :team="team"
                :scheduledDateTime="scheduledDateTime"
                :releaseDateTime="releaseDateTime"
            />

            <GoLive :episode="episode" :scheduledDateTime="scheduledDateTime"/>

            <EpisodeNotes :episode="episode"/>

            <ShowEpisodeManageEpisodeDescription :episode="episode" />

            <EpisodeRundown hidden />

            <ShowEpisodeManageBonusContent />

            <EpisodeFooter :can="props.can" :team="props.team" :episode="props.episode" :show="props.show"/>

        </div>
    </div>

</template>

<script setup>
import {computed, onBeforeMount, onMounted, ref} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useShowStore } from "@/Stores/ShowStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useUserStore } from "@/Stores/UserStore";
import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter";
import GoLive from "@/Components/ShowEpisodes/Manage/Elements/ShowEpisodeManageGoLive"
import Message from "@/Components/Modals/Messages";
import EpisodeRundown from "@/Components/ShowEpisodes/Manage/Elements/ShowEpisodeManageEpisodeRundown";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { useTimeAgo } from '@vueuse/core'
import EpisodeNotes from "@/Components/ShowEpisodes/Manage/Elements/ShowEpisodeManageEpisodeNotes";
import ShowEpisodeManageHeader from "@/Components/ShowEpisodes/Manage/Layout/ShowEpisodeManageHeader";
import ShowEpisodeManageBonusContent from "@/Components/ShowEpisodes/Manage/Elements/ShowEpisodeManageBonusContent.vue";
import ShowEpisodeManageEpisodeDescription
    from "@/Components/ShowEpisodes/Manage/Elements/ShowEpisodeManageEpisodeDescription.vue";
import ShowEpisodeManageNoticeModals from "@/Components/ShowEpisodes/Manage/Elements/ShowEpisodeManageNoticeModals.vue";

const videoPlayerStore = useVideoPlayerStore()
const showStore = useShowStore();
const teamStore = useTeamStore();
const userStore = useUserStore()

userStore.currentPage = 'episodes'
userStore.showFlashMessage = true;

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

// let showGoLive = ref(false)

const goLive = computed(() => ({
        'border-4 border-red-500': teamStore.goLiveDisplay
}))

</script>


