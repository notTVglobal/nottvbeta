<template>

    <Head :title="`Administrative Settings`"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>



    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-dark rounded text-light p-5">

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                                <span class="font-medium">
                                    {{ props.message }}
                                </span>
            </div>

            <header>
                <div class="flex justify-between mb-3">
                    <div class="mb-4">
                        <h1 class="text-3xl font-semibold">Administrative Settings</h1>
                    </div>
                    <div>
                        <div class="flex flex-wrap-reverse justify-end gap-2">
                                <Link :href="`/dashboard`">
                                    <button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                    >Dashboard
                                    </button>
                                </Link>
                        </div>
                    </div>
                </div>
            </header>


            <div>
                <div>
                    Content licenses
                </div>
                <div>
                    Episode Statuses
                </div>
                <div>
                    Show Statuses
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import {onMounted} from "vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"

import SectionTitle from "../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Components/SectionTitle";

let videoPlayer = useVideoPlayerStore()

onMounted(async () => {
    videoPlayer.makeVideoTopRight();

});

let props = defineProps({
    message: String
});
</script>
