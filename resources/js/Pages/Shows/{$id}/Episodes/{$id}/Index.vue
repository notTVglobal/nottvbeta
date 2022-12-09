<template>

    <Head :title="`${props.show.name}: ${props.episode.name}`"/>


    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">

        <div class="text-white bg-gray-900 rounded py-5 mb-10">

            <div class="flex justify-between -mb-10">

                <header class="p-5 mb-6">
                    <div class="py-4">
                        <span class="font-semibold text-xs uppercase">SHOW: </span>
                        <Link :href="`/shows/${props.show.slug}/`"
                              class="text-blue-400 hover:text-blue-600">
                            {{ props.show.name }}
                        </Link>
                    </div>
                    <div>
                        <h3 class="inline-flex items-center text-3xl font-semibold relative">
                            {{ props.episode.name }}
                        </h3>
                    </div>
                    <div class="text-xs">
                        <span class="uppercase">Episode Number: </span>
                        <span v-if="!episode.episodeNumber">{{ episode.id }}</span>
                        <span v-if="episode.episodeNumber">{{ episode.episodeNumber }}</span>
                    </div>
                    <div>
                        {{ formatDate(props.episode.created_at) }}
                    </div>
                </header>

                <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 py-5 mb-10">
                    <Link
                        v-if="props.can.manageShow"
                        :href="`/shows/${props.show.slug}/episode/${props.episode.slug}/manage`">
                        <button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Manage
                        </button>
                    </Link>
                    <Link v-if="props.can.viewCreator" :href="`/dashboard`">
                        <button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Dashboard
                        </button>
                    </Link>
                </div>


            </div>


            <div class="flex justify-between mb-3 px-5">

                <div v-if="!props.can.viewCreator">
                    <h3>
                        <Link :href="`/teams/${props.team.slug}`" class="text-blue-500 ml-2"> {{
                                props.team.name
                            }}
                        </Link>
                    </h3>
                </div>

            </div>
            <div class="flex justify-center w-full bg-black py-0">
<!--                                <img :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">-->

                <!--                TEST VIDEO EMBED FROM RUMBLE             -->
<!--                <iframe class="rumble" width="640" height="360" src="https://rumble.com/embed/v1nf3s7/?pub=4" frameborder="0" allowfullscreen></iframe>-->

                <div
                    class="flex justify-center shadow overflow-hidden border-b border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">

                    <img v-if="!props.episode.video_file_url && !props.episode.video_file_embed_code && props.episode.poster" :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">
                    <img v-if="!props.episode.video_file_url && !props.episode.video_file_embed_code && !props.episode.poster" :src="`/storage/images/EBU_Colorbars.svg.png`" alt="" class="w-1/2 mx-2">

                    <iframe v-if="props.episode.video_file_url && !props.episode.video_file_embed_code"
                            class="rumble" width="640" height="360" :src="`${props.episode.video_file_url}`" frameborder="0" allowfullscreen>
                    </iframe>
                    <div v-if="!props.episode.video_file_url && props.episode.video_file_embed_code" v-html="props.episode.video_file_embed_code">
                    </div>
                    <div v-if="props.episode.video_file_url && props.episode.video_file_embed_code" v-html="props.episode.video_file_embed_code">
                    </div>
                </div>

            </div>


            <div class="flex flex-col px-5">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">


                        <div class="flex space-x-6 mt-3">


                            <div class="mb-6 p-5">
                                <div class="font-semibold text-xs uppercase mb-3">EPISODE DESCRIPTION</div>
                                <div>{{ props.episode.description }}</div>
                            </div>
                        </div>

                        <div class="mb-6 p-5">
                            <div class="w-full bg-gray-900 text-2xl p-4 mb-8">CREATORS</div>


                            <div class="flex flex-row flex-wrap">
                                <div v-for="creator in props.creators.data"
                                     :key="creator.id"
                                     class="pb-8 bg-light dark:bg-gray-800">

                                    <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                                        <img :src="'/storage/' + creator.profile_photo_path" class="pb-2 rounded-full h-32 w-32 object-cover mb-2">
                                        <span class="light:text-gray-800 dark:text-gray-200 w-full text-center">{{ creator.name }}</span>
                                    </div>

<!--                            For now, we are just displaying the team members here.
                                This will make a good component that can be re-used across
                                the Show and Episode Index pages. Just pass in the creators prop.

                                We will add this when we have our Creators model setup
                                and creators attached to the credits table for this
                                show.                                                       -->

<!--                            <ShowCreatorsList />-->

                                </div>
                            </div>

                            <div class="w-full bg-gray-900 text-2xl p-4 mb-8">BONUS CONTENT</div>
                        </div>

                        <EpisodeFooter :team="props.team"/>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<script setup>
import {onBeforeMount, onMounted} from 'vue'
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useTeamStore} from "@/Stores/TeamStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
// import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader"
// import EpisodesList from "@/Components/ShowEpisodes/EpisodesList"
// import EpisodeCreditsList from "@/ComponentShows/Episodes/EpisodeCreditsList";
import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore();
let showStore = useShowStore();
let userStore = useUserStore()

videoPlayerStore.currentPage = 'episodes'

function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}

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
    show: Object,
    episode: Object,
    team: Object,
    creators: Object,
    message: String,
    can: Object,
});

teamStore.slug = props.team.slug;
teamStore.name = props.team.name;

// Inertia.reload({ only: ['video']})


</script>
