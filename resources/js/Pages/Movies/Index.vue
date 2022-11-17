<template>

    <Head :title="`Movie`"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>


    <header class="md:pageWidth pageWidthSmall">
        <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>


    </header>

    <div class="md:pageWidth pageWidthSmall">
        <div class="flex justify-center my-2">
            MOVIE NAME
        </div>


        <div class="flex justify-center my-2">
            <video class="w-1/2" controls>
                <source :src="`https://nottvbeta-staging.sfo3.cdn.digitaloceanspaces.com/uploads/movies/${props.video}`">
            </video>
        </div>

        <div class="flex justify-center my-2">
            MOVIE DESCRIPTION
        </div>

        <div class="flex justify-center my-2">
            TEAM NAME
        </div>

        <div class="flex justify-center my-2">
            CREATORS
        </div>

    </div>



</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { ref, onMounted } from "vue"
import Message from "@/Components/Modals/Messages"
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"

import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'


let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    message: ref(''),
    errors: ref(''),
    video: ref(''),
    // filters: Object,
});

let showMessage = ref(true);



</script>
