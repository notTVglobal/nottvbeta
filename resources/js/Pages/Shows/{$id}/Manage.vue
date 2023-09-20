<template>

    <Head :title="`Manage Show: ${props.show.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-50 rounded p-5 pb-36">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

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
                                :href="`/golive`"
                                v-if="teamStore.can.goLive">
                                <button
                                    class="px-4 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg disabled:bg-gray-400"
                                >Go Live
                                </button>
                            </Link>
                        </div>
                        <div class="">
                        <Link
                            :href="route('shows.createEpisode',{show: props.show.slug})"
                            v-if="teamStore.can.createEpisode">
                            <button
                                class="px-4 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded-lg disabled:bg-gray-400"

                            >Create Episode
                            </button>
                        </Link>
                        </div>
                        <div class="">
                            <Link
                                :href="`/shows/${show.slug}/edit`"
                                v-if="teamStore.can.editShow">
                                <button
                                    class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"
                                >Edit
                                </button>
                            </Link>
                        </div>
                        <div hidden>
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
                        <div><span class="text-xs  font-semibold uppercase">Team: </span><Link :href="`/teams/${team.slug}/manage`" class="text-blue-500 ml-2 uppercase font-bold"> {{ team.name }} </Link></div>
                        <div><span class="text-xs  font-semibold mr-2 uppercase">Show Runner: </span><span class="font-bold"> {{ show.showRunner }} </span></div>
                        <div><span class="text-xs  font-semibold mr-2 uppercase">Category: </span><span class="font-bold"> {{ show.categoryName }} </span></div>
                        <div><span class="text-xs  font-semibold mr-2 uppercase">Sub-category: </span><span class="font-bold"> {{ show.subCategoryName }} </span></div>
                    </div>
                </div>


                </div>

            </div>
            </header>


<!--            <div class="my-6 ml-10 w-3/4">-->
<!--                {{ teamStore.activeShow.description }}-->
<!--            </div>-->

            <div class="p-5 text-gray-100">
                <span class="uppercase text-xs font-semibold text-orange-300">SHOW NOTES: </span> {{ props.show.notes }}
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

<!-- This code doesn't work .. it's meant to become a header button that collapses/expands each section -->
<!--                            <button class="bg-orange-300 p-2 font-bold w-full text-left" type="button"-->
<!--                                    data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true"-->
<!--                                    aria-controls="collapseExample">Episodes-->
<!--                            </button>-->

<!--                            <div class="collapse" id="collapseExample">-->
                            <div>
                                <div class="bg-orange-300 p-2 font-bold text-black">Episodes</div>

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
                            <div class="bg-orange-300 p-2 font-bold text-black">Credits</div>
                            <div class="border-1 border-t mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                                In development. Not currently working.
                            </div>
                            <Link
                                :href="`#`"
                                v-if="teamStore.can.createAssignment">
                                <button
                                    class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                                    disabled
                                >Create Assignment
                                </button>
                            </Link>

                            <ShowCreditsList/>

                        </div>

                    </div>
                </div>
            </div>
            <ShowFooter :team="props.team" :show="props.show"/>
        </div>
    </div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
import {useTeamStore} from "@/Stores/TeamStore.js"
import {useUserStore} from "@/Stores/UserStore";
import ShowHeader from "@/Components/Shows/ShowHeader"
import ShowEpisodesList from "@/Components/Shows/Manage/ShowEpisodesList"
import ShowFooter from "@/Components/Shows/ShowFooter"
import ShowCreditsList from "@/Components/Shows/Manage/ShowCreditsList";
import Message from "@/Components/Modals/Messages";
import {onBeforeMount, onMounted, ref} from "vue";


let videoPlayerStore = useVideoPlayerStore()
let showStore = useShowStore();
let teamStore = useTeamStore();
let userStore = useUserStore()

userStore.currentPage = 'shows'
userStore.showFlashMessage = true;

onMounted(() => {
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
    episodes: Object,
    // filters: Object,
    can: Object,
});

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
teamStore.can = props.can;

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/shows', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));



</script>


