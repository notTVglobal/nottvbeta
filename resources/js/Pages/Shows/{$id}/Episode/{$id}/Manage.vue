<template>

    <Head :title="props.episode.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white rounded text-black p-5 mb-10">

            <EpisodeHeader
                :showRunnerName="props.showRunnerName"
                :poster="props.poster"
            />

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div
                                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                                role="alert"
                                v-if="props.message"
                            >
                                <span class="font-medium">
                                    {{props.message}}
                                </span>
                            </div>

                            <Episode />

                        </div>

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <EpisodeCreditsList />
                        </div>

                    </div>
                </div>
            </div>
            <EpisodeFooter />
        </div>
    </div>

</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import { ref, onMounted } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import EpisodeHeader from "@/Components/Shows/Episodes/EpisodeHeader"
import Episode from "@/Components/Shows/Episodes/Episode"
import EpisodeCreditsList from "@/Components/Shows/Episodes/EpisodeCreditsList";
import EpisodeFooter from "@/Components/Shows/Episodes/EpisodeFooter"

let videoPlayer = useVideoPlayerStore()
let showStore = useShowStore();
let teamStore = useTeamStore();

onMounted(async () => {
    videoPlayer.makeVideoTopRight();
    await function loadStores() {


    }

});

let props = defineProps({
    user: Object,
    show: ref([]),
    team: ref([]),
    episode: ref([]),
    poster: String,
    showRunnerName: String,
    message: String
});

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
teamStore.setActiveEpisode(props.episode);

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));


</script>


