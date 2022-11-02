
<template>

    <Head :title="props.show.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white text-black dark:bg-gray-900 dark:text-white rounded py-5 mb-10">

            <div class="flex justify-between">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative px-5">

                        {{ props.show.name }}
                    </h3>

                </div>

                <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4">
                    <Link
                        v-if="props.can.manageShow" :href="`/shows/${props.show.slug}/manage`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Manage</button>
                    </Link>
                    <Link v-if="props.can.viewCreator" :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Dashboard</button>
                    </Link>
                </div>

            </div>


            <header class="flex justify-between mb-3 px-5">
                <div v-if="!props.can.viewCreator">
                    <h3>
                        <Link :href="`/teams/${props.team.slug}`" class="text-blue-500 ml-2"> {{ props.team.name }} </Link>
                    </h3>
                </div>

            </header>


            <div class="flex justify-center w-full bg-black py-0 hidden">
                <img :src="'/storage/images/' + props.show.poster" alt="" class="w-1/2 mx-2">
            </div>



            <div class="flex flex-col px-5">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        <div class="flex space-x-6 mt-6">

                            <div class="mb-6 p-5">
                                <div class="font-semibold text-xs uppercase mb-3">SHOW DESCRIPTION</div>
                                <div>{{ props.show.description }}</div>
                            </div>
                        </div>

                        <div class="mb-6 p-5">
                            <div class="w-full bg-gray-300 dark:bg-gray-800 text-2xl p-4 mb-4">EPISODES</div>
                            <ShowEpisodesList :episodes="props.episodes" :show="props.show"/>

                            <div class="w-full bg-gray-300 dark:bg-gray-800 text-2xl p-4 my-8">CREATORS</div>

<!--                            We will add this when we have our Creators model setup
                                and creators attached to the credits table for this
                                show.                                                       -->

<!--                            <ShowCreatorsList />-->

                            <div class="w-full bg-gray-300 dark:bg-gray-800 text-2xl p-4 mb-8">POSTS</div>
                        </div>

                        <ShowFooter :team="props.team" />
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<script setup>
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import { onMounted } from 'vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import ShowEpisodesList from "@/Components/Shows/ShowEpisodesList"
// import ShowCreatorsList from "@/Components/Shows/ShowCreatorsList";
import ShowFooter from "@/Components/Shows/ShowFooter"

let videoPlayer = useVideoPlayerStore()
let teamStore = useTeamStore();
let showStore = useShowStore();

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    show: Object,
    team: Object,
    episodes: Object,
    message: String,
    can: Object,
});

teamStore.slug = props.team.slug;
teamStore.name = props.team.name;

</script>


