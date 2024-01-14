<template>

    <Head :title="`Manage Team: ${props.team.name}`"/>

    <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none">
        <div id="topDiv" class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 pb-36">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <!--            <TeamHeader v-bind="team" :memberSpots="props.team.memberSpots"/>-->

            <header class="mb-3 bg-gradient-to-r from-orange-200 via-orange-100 to-transparent p-4 text-black font-bold rounded-lg">
<!--            <header class="pulsing-background mb-3 p-4 text-white font-bold rounded-lg">-->


                <div class="flex justify-between items-end mb-3">
                    <div class="font-bold mb-4 text-black align-bottom text-lg">MANAGE TEAM</div>

                    <div>
                        <button
                            @click="userStore.btnRedirect('/dashboard')"
                            class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                        >Dashboard
                        </button>
                    </div>



                </div>
                <div class="flex justify-end mb-2">

                    <div class="flex flex-wrap-reverse justify-end gap-2">



                    </div>

                </div>

                            <TeamManageHeader
                                :team="team"
                                :teamLeader="teamLeader"
                                :logo="logo"
                                :can="can"
                                :image="image"
                                :message="message"
                            />


            </header>

<!--            <p class="mb-6 p-5 w-3/4">-->
<!--                {{ teamStore.description }}-->
<!--            </p>-->


            <div class="flex flex-col max-w-calc[100%-96rem]">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block w-full sm:px-6 lg:px-8 space-y-4">

                        <div @click="toggleComponent('teamShows')"
                             :class="{'rounded-t-lg': teamStore.openComponent === 'teamShows', 'rounded-lg': teamStore.openComponent !== 'teamShows'}"
                             class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
                            Shows
                        </div>
                        <div v-if="teamStore.openComponent === 'teamShows'">
                            <div class="mt-4 mb-12 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <TeamShowsList :shows="shows" :can="can" />
                            </div>
                        </div>

                        <div @click="toggleComponent('teamMembers')"
                             :class="{'rounded-t-lg': teamStore.openComponent === 'teamMembers', 'rounded-lg': teamStore.openComponent !== 'teamMembers'}"
                             class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
                            Team Members
                        </div>
                        <div v-if="teamStore.openComponent === 'teamMembers'">
                            <div class="mt-4 mb-12 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <TeamMembersList :creatorFilters="creatorFilters" :creators="creators" :can="can" />
                            </div>
                        </div>

                        <div @click="toggleComponent('teamAssignments')"
                             :class="{'rounded-t-lg': teamStore.openComponent === 'teamAssignments', 'rounded-lg': teamStore.openComponent !== 'teamAssignments'}"
                             class="accordion-header p-2 font-bold transition duration-300 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 overflow-hidden shadow-lg bg-orange-300 hover:bg-blue-100 dark:hover:bg-blue-900 text-black hover:text-blue-900 dark:text-blue-100 dark:hover:text-white">
                            Team Assignments
                        </div>
                        <div v-if="teamStore.openComponent === 'teamAssignments'">
                            <div class="mt-4 pb-6 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <TeamAssignmentsList/>
                            </div>
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

import Message from "@/Components/Modals/Messages";

const videoPlayerStore = useVideoPlayerStore()
const teamStore = useTeamStore();
const userStore = useUserStore()

userStore.currentPage = 'teams'
userStore.showFlashMessage = true;

// const openComponent = ref('teamShows');

const toggleComponent = (componentName) => {
    teamStore.openComponent = teamStore.openComponent === componentName ? null : componentName;
};

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
    teamCreator: Object,
    teamLeader: Object,
    members: Object,
    managers: Object,
    shows: Object,
    creators: Object,
    filters: Object,
    creatorFilters: Object,
    can: Object,
});

teamStore.setActiveTeam(props.team);
props.teamCreator && (teamStore.teamCreator = props.teamCreator);
props.teamLeader && (teamStore.teamLeader = props.teamLeader);
// teamStore.teamLeader.name = props.teamLeader ? props.teamLeader.name : 'No team leader assigned';
// if (props.teamLeader) {
//     teamStore.teamLeader = props.teamLeader

// }

teamStore.members = props.members;
teamStore.managers = props.managers;
teamStore.can = props.can;


</script>

<style scoped>
@keyframes pulse-bg {
    0%, 100% {
        background-color: rgba(255, 237, 213, 1); /* Fully opaque */
    }
    50% {
        background-color: rgba(255, 237, 213, 0.8); /* Slightly transparent */
    }
}

.pulsing-background {
    animation: pulse-bg 2s infinite ease-in-out;
    background-color: rgba(255, 237, 213, 1); /* Starting color */
}

</style>
