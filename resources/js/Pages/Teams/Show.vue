<template>

    <Head :title="team.name + ' Team'" />


    <div class="place-self-center flex flex-col gap-y-3 mr-96">

        <div class="bg-white rounded text-black p-5 mb-10">


            <TeamHeader />

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

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import TeamHeader from "@/Components/Teams/TeamHeader";
import TeamMembersList from "@/Components/Teams/TeamMembersList";
import TeamFooter from "@/Components/Teams/TeamFooter";
import { useTeamStore } from "@/Stores/TeamStore.js";

let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"

let team = useTeamStore();
team.fill();

setTimeout(() => {
    team.spots = 10;
}, 2000)

</script>
