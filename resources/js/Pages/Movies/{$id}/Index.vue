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
        <div class="p-4">
            <div>
                <h3 class="inline-flex items-center text-3xl font-semibold relative">
                    {{ movie.name }}
                </h3>
            </div>
            <div>
                {{ formatDate(movie.created_at) }}
            </div>
        </div>


        <div class="flex justify-center my-2">
            <video class="w-1/2" controls>
<!--                <source :src="`https://beta-staging-files.not.tv/uploads/movies/${movie.filePath}`">-->
                    <source :src="`https://nottvbeta-staging.sfo3.cdn.digitaloceanspaces.com/uploads/movies/${movie.filePath}`">
            </video>

        </div>

        <div class="flex space-x-6 mt-3">


            <div class="mb-6 p-5">
                <div class="font-semibold text-xs uppercase mb-3">MOVIE DESCRIPTION</div>
                <div>{{ movie.description }}</div>
            </div>
        </div>


        <div class="mb-6 p-5">
            <div class="w-full bg-gray-900 text-2xl p-4 mb-8">CREATORS</div>


            <div class="flex flex-row flex-wrap">
<!--                <div v-for="creator in props.creators.data"-->
<!--                     :key="creator.id"-->
<!--                     class="pb-8 bg-light dark:bg-gray-800">-->

<!--                    <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">-->
<!--                        <img :src="'/storage/' + creator.profile_photo_path" class="pb-2 rounded-full h-32 w-32 object-cover mb-2">-->
<!--                        <span class="light:text-gray-800 dark:text-gray-200 w-full text-center">{{ creator.name }}</span>-->
<!--                    </div>-->

                    <!--                            For now, we are just displaying the team members here.
                                                    This will make a good component that can be re-used across
                                                    the Show and Episode Index pages. Just pass in the creators prop.

                                                    We will add this when we have our Creators model setup
                                                    and creators attached to the credits table for this
                                                    show.                                                       -->

                    <!--                            <ShowCreatorsList />-->

<!--                </div>-->
            </div>

            <div class="w-full bg-gray-900 text-2xl p-4 mb-8">BONUS CONTENT</div>
        </div>

        <div class="flex justify-end mt-6 pr-2">
            <!-- Paginator -->
            <!--                            <Pagination :links="`#`" class="mt-6"/>-->
            <Link :href="`/teams/${movie.teamSlug}`" class="text-blue-500 ml-2"> {{ movie.teamName }} © {{ movie.copyrightYear }} </Link>
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
    movie: Object,
    teamName: String,
    teamSlug: String,
    creators: Object,
    message: ref(''),
    errors: ref(''),
    video: ref(''),
    // filters: Object,
});

let showMessage = ref(true);



</script>
