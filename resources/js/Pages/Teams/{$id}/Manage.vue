<template>

    <Head :title="`Manage Team: ${props.team.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">
        <div id="topDiv" class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 pb-36">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <!--            <TeamHeader v-bind="team" :memberSpots="props.team.memberSpots"/>-->

            <header>
                <div class="flex justify-between mb-3 pt-6">
                    <div class="gap-2">
                        <div class="font-bold mb-4 text-orange-400">MANAGE TEAM</div>
                        <div>
                            <TeamManageHeader
                                :team="props.team"
                                :teamLeader="props.teamLeader"
                                :logo="props.logo"
                                :image="props.image"
                                :message="props.message"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-wrap-reverse justify-end gap-2">
                            <Link
                                :href="`/shows/create`"
                                v-if="teamStore.can.editTeam">
                                <button
                                    class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                                >Create Show
                                </button>
                            </Link>
                            <button
                                class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                                @click="openModal"
                                :disabled="!teamStore.spotsRemaining"
                                v-if="teamStore.can.editTeam"
                            >Add Member ({{ teamStore.spotsRemaining }} spots left)</button>
                            <Link
                                v-if="can.editTeam" :href="`/teams/${team.slug}/edit`">
                                <button
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                                >Edit
                                </button>
                            </Link>
                            <Link :href="`/dashboard`">
                                <button
                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    hidden
                                >Dashboard
                                </button>
                            </Link>
                        </div>


                        <div class="flex justify-end mt-6">
                            <div class="flex flex-col">
                                <div><span class="text-xs font-semibold mr-2 uppercase">Team Leader: </span>
                                    <span class="font-bold">{{ teamLeader }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </header>

<!--            <p class="mb-6 p-5 w-3/4">-->
<!--                {{ teamStore.description }}-->
<!--            </p>-->


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="mt-4 mb-12 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamShowsList :shows="props.shows.data" />
                            <!-- Paginator -->
                            <Pagination :data="props.shows" class="mt-6"/>
                        </div>

                        <div class="mt-4 mb-12 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamMembersList :creatorFilters="props.creatorFilters" :creators="props.creators"/>
                        </div>

                        <div class="mt-4 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamAssignmentsList/>
                        </div>

                        <!--  <TeamFooter />  -->

                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import TeamManageHeader from "@/Components/Teams/Manage/TeamManageHeader"
import TeamMembersList from "@/Components/Teams/Manage/TeamMembersList"
import TeamShowsList from "@/Components/Teams/Manage/TeamShowsList"
import TeamAssignmentsList from "@/Components/Teams/Manage/TeamAssignmentsList"
import Pagination from "@/Components/Pagination"
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore();
let userStore = useUserStore()

userStore.currentPage = 'teams'
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
    team: Object,
    logo: String,
    image: Object,
    teamLeader: String,
    members: Object,
    shows: Object,
    creators: Object,
    filters: Object,
    creatorFilters: Object,
    can: Object,
});

teamStore.setActiveTeam(props.team);
teamStore.members = props.members;
teamStore.can = props.can;

function openModal() {
    teamStore.showModal = true;
}

</script>
