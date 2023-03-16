<template>

    <Head :title="props.team.name" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">

        <div class="light:bg-light dark:bg-gray-800 rounded light:text-black dark:text-gray-50 p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative">
                        <img :src="'/storage/images/' + props.logo" alt="" class="w-20 mr-2">
                        {{ props.team.name }}
                    </h3>

                </div>
                <div class="flex flex-wrap-reverse justify-end gap-2">
                    <Link
                        v-if="props.can.editTeam" :href="`/teams/${props.team.slug}/manage`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Manage</button>
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

                    <div class="w-full light:bg-gray-300 dark:bg-gray-900 light:text-black dark:text-gray-50 text-2xl p-4 mb-8">SHOWS</div>

                        <TeamShowsList :shows="props.shows" />

                    <div class="w-full light:bg-gray-300 dark:bg-gray-900 light:text-black dark:text-gray-50  text-2xl p-4 mb-8">CREATORS</div>

                        <div class="flex flex-row flex-wrap">
                            <div v-for="creator in props.creators.data"
                                 :key="creator.id"
                                 class="pb-8 dark:bg-light light:bg-gray-800">

                                <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                                    <img :src="'/storage/' + creator.profile_photo_path" class="pb-2 rounded-full h-32 w-32 object-cover mb-2">
                                    <span class="dark:text-gray-800 light:text-gray-200 w-full text-center">{{ creator.name }}</span>
                                </div>
                            </div>
                        </div>

                        <!--                            For now, we are just displaying the team members here.
                                                        This will make a good component that can be re-used across
                                                        the Show and Episode Index pages. Just pass in the creators prop.

                                                        We will add this when we have our Creators model setup
                                                        and creators attached to the credits table for this
                                                        show.                                                       -->

                        <!--                            <ShowCreatorsList />-->

                        <!--  <TeamFooter />  -->

                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import {onBeforeMount, onMounted} from "vue"

import {useUserStore} from "@/Stores/UserStore";
import TeamShowsList from "@/Components/Teams/TeamShowsList.vue";

let videoPlayerStore = useVideoPlayerStore();
let teamStore = useTeamStore();
let userStore = useUserStore()

videoPlayerStore.currentPage = 'teams'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    user: Object,
    team: Object,
    logo: String,
    shows: Object,
    creators: Object,
    message: String,
    filters: Object,
    can: Object,
});

teamStore.setActiveTeam(props.team);
teamStore.can = props.can;


</script>
