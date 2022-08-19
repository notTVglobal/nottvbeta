<template>

    <Head :title="team.name + ' Team'" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>


    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black p-5 mb-10">


            <TeamHeader @add="showModal = true" />

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <TeamMembersList />

<!--                        <TeamFooter />-->


                        </div>
                        <div class="bg-orange-300 w-full mt-6 p-2 rounded font-bold">Team Assignments</div>
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
import { useTeamStore } from "@/Stores/TeamStore.js"
import TeamHeader from "@/Components/Teams/TeamHeader"
import TeamMembersList from "@/Components/Teams/TeamMembersList"
import TeamFooter from "@/Components/Teams/TeamFooter"
import Modal from "@/Components/Modal"
import { ref } from 'vue'
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let team = useTeamStore();
team.fill();

let showModal = ref(false);

</script>
