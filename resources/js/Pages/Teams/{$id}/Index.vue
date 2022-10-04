<template>

    <Head :title="props.team.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>


    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black p-5 mb-10">

<!--            <TeamHeader v-bind="team" :memberSpots="props.team.memberSpots"/>-->



            <header class="flex justify-between mb-3">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative">
                        <img :src="`/storage/images/oeV64kpa339M8tmLEZrERCb7bLfuAy8BIqJ17x8t.png`" alt="" class="w-20 mr-2">
                        {{ props.team.name }}
                        <div
                            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-0.5">
                            {{ props.team.memberSpots }}
                        </div>
                    </h3>

                </div>
                <div class="flex flex-wrap justify-end space-x-4">
                    <Link
                        :href="`/teams/${props.team.id}/edit`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Edit Team</button>
                    </Link>
                    <Link :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Dashboard</button>
                    </Link>
                </div>
            </header>

            <p class="mb-6 p-5">
                {{ props.team.description }}
            </p>

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                                <span class="font-medium">
                                    {{props.message}}
                                </span>
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamMembersList :memberSpots="props.team.memberSpots" :totalSpots="props.team.totalSpots"/>
                        </div>

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamShowsList :shows="props.shows.data"/>
                            <!-- Paginator -->
                            <Pagination :links="props.shows.links" class="mt-6"/>
                        </div>

                        <div class="mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <TeamAssignmentsList />
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
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import TeamHeader from "@/Components/Teams/TeamHeader"
import TeamMembersList from "@/Components/Teams/TeamMembersList"
import TeamFooter from "@/Components/Teams/TeamFooter"
import Modal from "@/Components/Modal"
import { ref, reactive, computed } from 'vue'
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import TeamShowsList from "@/Components/Teams/TeamShowsList";
import TeamAssignmentsList from "@/Components/Teams/TeamAssignmentsList";
import Pagination from "@/Components/Pagination";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let teamStore = useTeamStore();
// team.fill();

let props = defineProps({
    team: Object,
    shows: Object,
    message: String,
    filters: Object,
});

// let team_id = ref(props.filters.team_id)


teamStore.setActiveTeam(props.team.id);

let showModal = ref(false);
let a = ref(props.team.memberSpots);
let b = ref(props.team.totalSpots);
let spotsRemaining = computed(() => b - a);

</script>
