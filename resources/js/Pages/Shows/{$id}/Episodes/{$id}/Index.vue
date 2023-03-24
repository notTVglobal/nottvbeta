<template>

    <Head :title="`${props.show.name}: ${props.episode.name}`"/>


    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">
        <div class="text-white bg-gray-900 rounded py-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div v-if="props.can.editShow || props.can.manageShow" class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 py-5">
                <Link
                    v-if="props.can.editShow"
                    :href="`/shows/${props.show.slug}/episode/${props.episode.slug}/edit`">
                    <button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Edit
                    </button>
                </Link>
                <Link
                    v-if="props.can.manageShow" :href="`/shows/${props.show.slug}/manage`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Manage Show</button>
                </Link>
            </div>

                <header class="p-5 mb-6">
                    <div class="flex justify-between mb-3 px-5">
                        <div class="">
                            <div class="mb-4">
                                <h3 class="inline-flex items-center text-3xl font-semibold relative">
                                    {{ props.episode.name }}
                                </h3>
                            </div>
                            <div class="text-xs space-y-1">
                                <span class="uppercase">Episode Number: </span>
                                <span v-if="!episode.episodeNumber">{{ episode.id }}</span>
                                <span v-if="episode.episodeNumber">{{ episode.episodeNumber }}</span>
                            </div>
                            <div>
                                {{ formatDate(props.episode.created_at) }}
                            </div>
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="">
                                <span class="text-xs uppercase">Show: </span>
                                <Link :href="`/shows/${props.show.slug}/`"
                                      class="text-blue-400 hover:text-blue-600 text-sm uppercase font-semibold">
                                    {{ props.show.name }}</Link>
                            </div>
                            <div class="">
                                <span class="text-xs uppercase">Category: </span>
                                <span class="text-sm uppercase font-semibold">{{ props.show.categoryName }}</span>
                            </div>
                            <div class="pb-4">
                                <span class="text-xs uppercase">Sub-category: </span>
                                <span class="text-sm uppercase font-semibold">{{ props.show.categorySubName }}</span>
                            </div>
                            <div v-if="props.can.viewCreator">
                                <span class="text-xs uppercase">Team:</span><Link :href="`/teams/${props.team.slug}`" class="text-blue-500 ml-2"> <span class="text-sm uppercase font-semibold">{{ props.team.name }}</span></Link>
                            </div>
                        </div>

                    </div>
                </header>






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
                            <div class="w-full bg-gray-800 text-2xl p-4 mb-8">CREDITS</div>


                            <div class="flex flex-row flex-wrap">
                                <div v-for="creator in props.creators.data"
                                     :key="creator.id"
                                     class="pb-8 light:bg-light dark:bg-gray-900">

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

                            <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
                        </div>

                        <EpisodeFooter :team="props.team" :epsiode="props.episode"/>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useUserStore } from "@/Stores/UserStore";
// import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader"
// import EpisodesList from "@/Components/ShowEpisodes/EpisodesList"
// import EpisodeCreditsList from "@/ComponentShows/Episodes/EpisodeCreditsList";
import EpisodeFooter from "@/Components/ShowEpisodes/EpisodeFooter"
import Message from "@/Components/Modals/Messages";

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

let showMessage = ref(true);

</script>
