<template>

    <Head :title="props.show.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white rounded text-black p-5 mb-10">

            <ShowHeader
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

                            <ShowEpisodesList :episodes="props.episodes" :show="props.show"/>

                            <!--                            <table class="min-w-full divide-y divide-gray-200">-->
                            <!--                                <tbody class="bg-white divide-y divide-gray-200">-->
                            <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
                            <!--                                    <td class="px-6 py-4 whitespace-nowrap">-->
                            <!--                                        <div class="flex items-center">-->
                            <!--                                            <div>-->
                            <!--                                                <div class="text-sm font-medium text-gray-900">-->
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            <!--                                                </div>-->
                            <!--                                            </div>-->
                            <!--                                        </div>-->
                            <!--                                    </td>-->

                            <!--                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">-->
                            <!--                                        <Link :href="`/admin/users/edit/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>-->
                            <!--                                    </td>-->
                            <!--                                </tr>-->
                            <!--                                </tbody>-->
                            <!--                            </table>-->

                            <!--                            &lt;!&ndash; Paginator &ndash;&gt;-->
                            <!--                            <Pagination :links="episode.links" class="mt-6"/>-->

                        </div>

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <ShowCreditsList />
                        </div>

                    </div>
                </div>
            </div>
            <ShowFooter />
        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import ShowHeader from "@/Components/Shows/ShowHeader"
import ShowEpisodesList from "@/Components/Shows/ShowEpisodesList"
import ShowFooter from "@/Components/Shows/ShowFooter"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import ShowCreditsList from "@/Components/Shows/ShowCreditsList";
import {onMounted, ref, watch} from "vue";
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()
let showStore = useShowStore();
let teamStore = useTeamStore();
// showStore.fill();

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    show: Object,
    team: Object,
    episodes: Object,
    poster: String,
    showRunnerName: String,
    // filters: Object,
    message: String
});

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));


</script>


