<template>

    <Head :title="props.team.name + ' Team'" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>


    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black p-5 mb-10">


<!--            <TeamHeader v-bind="team" @add="showModal = true" />-->


            <div class="flex justify-between mb-3">
                    <Link :href="`/teams`" class="mr-2 text-blue-800 hover:text-blue-600">All Teams</Link>
                    <Link :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Dashboard</button>
                    </Link>
            </div>

            <header class="flex justify-between">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative">
                        <img :src="`../../images/Ping.png`" alt="" class="w-20 mr-2">
                        {{ props.team.name }} Team
                        <div
                            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-2">
                            {{ props.team.memberSpots }}
                        </div>
                    </h3>
                    <div class="mt-2 p-5">
                        <p class="mb-6">
                            {{ props.team.description }}
                        </p>
                    </div>
                </div>
                <div class="space-y-3 mr-8 mb-6">
                    <div class="">
                        <Link
                            class="bg-blue-500 hover:bg-blue-600 text-white ml-6 px-4 py-3 rounded disabled:bg-gray-400"
                            :href="`/teams/${props.team.id}/edit`"
                        >Edit Team</Link>
                    </div>
                    <div class="">
                        <button
                            class="bg-green-500 hover:bg-green-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                            :disabled="! spotsRemaining"
                            @click="$emit('add')"
                        >Add Member ({{ spotsRemaining }} spots left)</button>
                    </div>
                    <div class="">
                        <button
                            class="bg-green-500 hover:bg-green-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                        >Create Assignment</button>
                    </div>
                </div>
            </header>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <TeamMembersList />

<!--                        <TeamFooter />-->


                        </div>
                        <div class="bg-orange-300 w-full mt-6 p-2 rounded font-bold">Team Assignments</div>
                        <div class="p-2">Coming Soon!</div>

                        <div class="bg-orange-300 w-full mt-6 p-2 rounded font-bold">Shows</div>
                        <div class="p-2">Coming Soon!</div>
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
// import { useTeamStore } from "@/Stores/TeamStore.js"
import TeamHeader from "@/Components/Teams/TeamHeader"
import TeamMembersList from "@/Components/Teams/TeamMembersList"
import TeamFooter from "@/Components/Teams/TeamFooter"
import Modal from "@/Components/Modal"
import { ref, reactive, computed } from 'vue'
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"
//
// let team = useTeamStore();
// team.fill();

let props = defineProps({
    team: Object
});

let showModal = ref(false);
let a = reactive(props.team.memberSpots)
let b = reactive(props.team.totalSpots);
let spotsRemaining = computed(() => b - a)

</script>
