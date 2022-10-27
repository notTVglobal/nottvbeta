<template>

    <Head :title="`Manage Team: ${props.team.name}`"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>


    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black p-5 mb-10">

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="message"
            >
        <span class="font-medium">
            {{ message }}
        </span>
            </div>

            <!--            <TeamHeader v-bind="team" :memberSpots="props.team.memberSpots"/>-->

            <header>
                <div class="flex justify-between mb-3">
                    <div class="gap-2">
                        <div class="font-bold mb-4 text-orange-400">MANAGE TEAM</div>
                        <div>
                            <TeamManageHeader
                                :team="props.team"
                                :teamLeader="props.teamLeader"
                                :logo="props.logo"
                                :can="props.can"
                                :message="props.message"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-wrap-reverse justify-end gap-2">
                            <Link
                                v-if="can.editTeam" :href="`/teams/${team.slug}/edit`">
                                <button
                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                >Edit
                                </button>
                            </Link>
                            <Link :href="`/dashboard`">
                                <button
                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                >Dashboard
                                </button>
                            </Link>
                        </div>


                        <div class="flex justify-end mt-6">
                            <div class="flex flex-col">
                                <div><span class="text-xs capitalize font-semibold mr-2">Team Leader: </span>
                                    {{ teamLeader }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </header>

            <p class="mb-6 p-5 w-3/4">
                {{ team.description }}
            </p>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamShowsList :shows="props.shows.data"/>
                            <!-- Paginator -->
                            <Pagination :links="props.shows.links" class="mt-6"/>
                        </div>

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamMembersList :memberSpots="props.team.memberSpots" :totalSpots="props.team.totalSpots"/>
                        </div>

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamAssignmentsList/>
                        </div>

                        <!--  <TeamFooter />  -->

                    </div>
                </div>
            </div>

        </div>
    </div>

    <Teleport to="body">
        <Modal :show="showModal" @close="showModal = false">

            <template #header>
                Add a new team member
            </template>
            <template #default>
                <div class="pb-2">
                    Send an email invitation to join your team.
                </div>
                <form>
                    <div class="flex gap-2">
                        <input
                            type="email"
                            placeholder="Email Address..."
                            class="rounded flex-1"
                        >
                        <button class="bg-gray-300 rounded-md w-20 p-2 hover:bg-gray-400 text-sm">Add</button>
                    </div>
                </form>
            </template>
            <template #footer>
                <button @click="showModal = false" class="text-blue-600 hover:text-gray-500">Cancel</button>
            </template>
        </Modal>
    </Teleport>

</template>


<script setup>
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {ref, computed, onMounted} from "vue"
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useTeamStore} from "@/Stores/TeamStore.js"
import Modal from "@/Components/Modal"
import Pagination from "@/Components/Pagination"
import TeamManageHeader from "@/Components/Teams/Manage/TeamManageHeader"
import TeamMembersList from "@/Components/Teams/TeamMembersList"
import TeamShowsList from "@/Components/Teams/TeamShowsList"
import TeamAssignmentsList from "@/Components/Teams/TeamAssignmentsList"

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore();

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    team: Object,
    logo: String,
    teamLeader: String,
    shows: Object,
    message: String,
    filters: Object,
    can: Object,
});

teamStore.setActiveTeam(props.team);

let showModal = ref(false);
let a = ref(props.team.memberSpots);
let b = ref(props.team.totalSpots);
let spotsRemaining = computed(() => b - a);

</script>
