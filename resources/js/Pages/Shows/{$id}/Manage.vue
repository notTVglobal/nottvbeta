<template>

    <Head :title="`Manage Show: ${props.show.name}`"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>



    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white rounded text-black p-5 mb-10">

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
                    <div class="font-bold mb-4 text-orange-400">MANAGE SHOW</div>
                    <div>
                        <ShowHeader
                            :show="props.show"
                            :team="props.team"
                        />
                    </div>
                </div>


                <div>
                    <div class="flex flex-wrap-reverse justify-end gap-2">
                        <div class="">
                            <Link
                                :href="`/golive`">
                                <button
                                    class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                                >Go Live
                                </button>
                            </Link>
                        </div>
                        <div class="">
                            <Link
                                :href="`/shows/${show.slug}/edit`">
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
                        <div><span class="text-xs capitalize font-semibold">Team: </span><Link :href="`/teams/${team.slug}/manage`" class="text-blue-500 ml-2"> {{ team.name }} </Link></div>
                        <div><span class="text-xs capitalize font-semibold mr-2">Show Runner: </span> {{ show.showRunner }} </div>
                    </div>
                </div>


                </div>

            </div>
            </header>


            <div class="my-6 ml-10 w-3/4">
                {{ teamStore.activeShow.description }}
            </div>




            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                            <button class="bg-orange-300 p-2 font-bold w-full text-left" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample">Episodes
                            </button>
                            <div class="collapse" id="collapseExample">
                                <Link
                                    :href="route('shows.createEpisode',{show: props.show.slug})">
                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                                    >Create Episode
                                    </button>
                                </Link>

                                <ShowEpisodesList :episodes="props.episodes" :show="props.show"/>
                            </div>

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
                            <div class="bg-orange-300 p-2 font-bold">Credits</div>
                            <Link
                                :href="`#`">
                                <button
                                    class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                                    disabled
                                >Create Assignment
                                </button>
                            </Link>
                            <div class="p-2">This section is in development.</div>

                            <ShowCreditsList/>

                        </div>

                    </div>
                </div>
            </div>
            <ShowFooter :team="props.team"/>
        </div>
    </div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
import {useTeamStore} from "@/Stores/TeamStore.js"
import ShowHeader from "@/Components/Shows/ShowHeader"
import ShowEpisodesList from "@/Components/Shows/Manage/ShowEpisodesList"
import ShowFooter from "@/Components/Shows/ShowFooter"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import ShowCreditsList from "@/Components/Shows/Manage/ShowCreditsList";
import {onMounted, ref, watch} from "vue";
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()
let showStore = useShowStore();
let teamStore = useTeamStore();

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    show: Object,
    team: Object,
    episodes: Object,
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


