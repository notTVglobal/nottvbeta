<template>

    <Head :title="props.team.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>


    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative">
                        <img :src="'/storage/images/' + props.logo" alt="" class="w-20 mr-2">
                        {{ props.team.name }}
                    </h3>

                </div>
                <div class="flex flex-wrap-reverse justify-end gap-2">
                    <Link
                        v-if="props.can.manageTeam" :href="`/teams/${props.team.id}/manage`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Manage</button>
                    </Link>
                    <Link
                        v-if="props.can.editTeam" :href="`/teams/${props.team.id}/edit`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Edit</button>
                    </Link>
                    <Link v-if="props.user.role_id === 4" :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Dashboard</button>
                    </Link>
                </div>
            </header>

            <p class="mb-6 p-5">
                {{ props.team.description }}
            </p>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                    <div class="w-full bg-gray-300 text-2xl p-4 mb-8">SHOWS</div>

                        <!-- Paginator -->
                        <Pagination :links="props.shows.links" class="mb-6"/>

<div class="flex flex-row flex-wrap">
                            <div
                                v-for="show in props.shows.data"
                                :key="show.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <div
                                    class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white"
                                >
                                    <!--                                                    <img :src="`/storage/images/${show.poster}`" class="rounded-full h-20 w-20 object-cover">-->
                                    <Link :href="`/shows/${show.id}`" class="text-blue-800 hover:text-blue-600">
                                    <img :src="'/storage/images/' + show.poster" class="rounded-full h-32 w-32 object-cover"></Link>
                                </div>
                                <div
                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white break-words grow-0 w-32"
                                >
                                    <Link :href="`/shows/${show.id}`" class="text-blue-800 hover:text-blue-600">{{ show.name }}</Link>
                                </div>
                            </div>
</div>

                    <div class="w-full bg-gray-300 text-2xl p-4 mb-8">CREATORS</div>

                    <div class="w-full bg-gray-300 text-2xl p-4 mb-8">POSTS</div>

                        <!--  <TeamFooter />  -->

                    </div>
                </div>
            </div>

        </div>
    </div>

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
    user: Object,
    team: Object,
    logo: String,
    shows: Object,
    message: String,
    filters: Object,
    can: Object,
});


</script>
