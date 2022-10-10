
<template>

    <Head :title="props.show.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black py-5 mb-10">

            <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4">
                <Link
                    v-if="props.can.manageShow" :href="`/shows/${props.show.id}/manage`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Manage</button>
                </Link>
                <Link
                    v-if="props.can.editShow" :href="`/shows/${props.show.id}/edit`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Edit</button>
                </Link>
                <Link v-if="props.can.viewCreator" :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>
            <header class="flex justify-between mb-3 px-5">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative">

                        {{ props.show.name }}
                    </h3>

                </div>
                <div v-if="!props.can.viewCreator">
                    <h3>
                        <Link :href="`/teams/${teamId}`" class="text-blue-500 ml-2"> {{ teamName }} </Link>
                    </h3>
                </div>

            </header>
            <div class="flex justify-center w-full bg-black py-0">
                <img :src="'/storage/images/' + props.posterName" alt="" class="w-1/2 mx-2">
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
                            <div class="w-full bg-gray-300 text-2xl p-4 mb-8">EPISODES</div>

                            <div class="w-full bg-gray-300 text-2xl p-4 mb-8">CREDITS</div>

                            <div class="w-full bg-gray-300 text-2xl p-4 mb-8">POSTS</div>
                        </div>

                        <ShowFooter :teamName="props.teamName" :teamId="props.teamId"/>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { ref } from 'vue'
import ShowHeader from "@/Components/Shows/ShowHeader"
import ShowEpisodesList from "@/Components/Shows/ShowEpisodesList"
import ShowFooter from "@/Components/Shows/ShowFooter"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import ShowCreditsList from "@/Components/Shows/ShowCreditsList";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()
let showStore = useShowStore();
showStore.fill();

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    // user: Object,
    show: Object,
    posterName: ref(String),
    teamId: Number,
    teamName: String,
    showRunner: String,
    // episodes: Object,
    message: String,
    can: Object,
});

</script>


