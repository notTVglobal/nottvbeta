
<template>

    <Head :title="props.show.name" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div>
                    <h3 class="inline-flex items-center text-3xl font-semibold relative">
                        <img :src="'/storage/images/' + props.poster" alt="" class="w-20 mr-2">
                        {{ props.show.name }}
                    </h3>

                </div>
                <div class="flex flex-wrap-reverse justify-end gap-2">
                    <Link
                        v-if="props.can.manageShow" :href="`/shows/${props.show.id}/manage`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Manage</button>
                    </Link>
                    <Link
                        v-if="can.editShow" :href="`/shows/${props.show.id}/edit`"><button
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
                {{ props.show.description }}
            </p>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                        BODY

<!--                        <ShowFooter :show="props.team.name"/>-->
                        <ShowFooter :show="props.team.name" :team_id="props.team.id"/>
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
    user: Object,
    show: Object,
    poster: String,
    team: Object,
    showRunner: String,
    episodes: Object,
    message: String,
    can: Object,
});


</script>


