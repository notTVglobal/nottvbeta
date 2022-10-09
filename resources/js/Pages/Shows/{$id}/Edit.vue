<template>

    <Head :title="title"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <ShowEditHeader :show="props.show" :team="props.team"/>



            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                            <!--            <div class="max-w-lg mx-auto mt-8">-->

                            <ShowEditBody
                                :show="props.show"
                                :images="props.images"
                                :poster="props.poster"
                            />


                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3"

import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {ref, reactive} from 'vue'
import {Inertia} from "@inertiajs/inertia";
import ShowEditHeader from "@/Components/Shows/Edit/ShowEditHeader";
import ShowEditBody from "@/Components/Shows/Edit/ShowEditBody";


let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

let showStore = useShowStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    user: Object,
    show: Object,
    team: {
        type: Object,
        name: String,
        id: Number,
    },
    poster: Object,
    images: {
        data: {
            0: {
                name: String,
                id: Number,
            },
        },
    },
});

showStore.posterName = props.poster;
showStore.posterId = props.show.image_id;

let title = "Edit > " + props.team.name;


</script>
