<template>

    <Head :title="team.name + ' Team'" />


    <div class="place-self-center flex flex-col gap-y-3 pageWidth">

        <div class="bg-white rounded text-black p-5 mb-10">


            <TeamHeader @add="showModal = true" />

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                        <TeamMembersList />

                        <TeamFooter />

                        </div>
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
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import TeamHeader from "@/Components/Teams/TeamHeader";
import TeamMembersList from "@/Components/Teams/TeamMembersList";
import TeamFooter from "@/Components/Teams/TeamFooter";
import { useTeamStore } from "@/Stores/TeamStore.js";
import Modal from "@/Components/Modal";
import { ref } from 'vue';

let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false

let team = useTeamStore();
team.fill();

let showModal = ref(false);

</script>
