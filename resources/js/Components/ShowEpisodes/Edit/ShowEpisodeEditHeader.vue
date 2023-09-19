<template>

    <div class="flex justify-between my-6">
        <div>
            <div class="font-bold mb-4 text-red-700">EDIT EPISODE</div>
            <h1 class="text-3xl">
                <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="text-red-700 font-bold uppercase">{{ episode.name }}</Link>
            </h1>
        </div>
        <div>
            <button
                @click="back"
                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
            >Cancel
            </button>

        </div>
    </div>

    <div class="flex flex-wrap w-full justify-between">
        <div class="space-y-1 text-black dark:text-white">
            <div class="mb-6"><span class="text-xs uppercase font-semibold">Episode #: </span>
                <span v-if="episode.episode_number" class="font-bold uppercase">{{ episode.episode_number }}</span>
                <span v-if="!episode.episode_number" class="font-bold uppercase">{{ episode.id }}</span>
            </div>
            <div class=""><span class="text-xs uppercase font-semibold">Show: </span>
                <Link :href="`/shows/${show.slug}/manage`">
                    <span class="font-bold uppercase text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">{{ show.name }}</span>
                </Link>
            </div>
            <div class=""><span class="text-xs uppercase font-semibold">Category: </span>
                <span class="font-bold uppercase">{{ show.showCategoryName }}</span>
                <span class="text-xs">
                    (change the category on the
                    <Link :href="`/shows/${show.slug}/edit`"
                          class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">
                        show edit page</Link>)
                </span>
            </div>
            <div class=""><span class="text-xs uppercase font-semibold">Sub-category: </span>
                <span class="font-bold uppercase">{{ show.subCategoryName }}</span>
                <span class="text-xs">
                    (change the sub-category on the
                    <Link :href="`/shows/${show.slug}/edit`"
                          class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">
                        show edit page</Link>)
                </span>
            </div>
            <div class=""><span class="text-xs uppercase font-semibold">Team: </span>
                <Link :href="`/teams/${team.slug}/manage`">
                    <span class="dark:font-bold uppercase text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">{{ team.name }}</span>
                </Link>
            </div>

        </div>



        <div class="flex flex-col rounded justify-end w-fit bg-white dark:bg-black my-6 p-3">
            <div class="block mb-2 uppercase font-bold text-xs text-black dark:text-white"
                 for="name"
            >
                Episode Video
            </div>
            <div>

                <div
                    v-if="!episode.video_id"
                    class="flex justify-center shadow overflow-hidden border-b border-gray-200 bg-white dark:bg-black text-2xl sm:rounded-lg p-5">

                    <div v-if="!episode.video_url">NO VIDEO</div>
                    <video id="episodeEditVideoPlayer" v-if="episode.video_url"
                           class="video-js w-fit" controls>
                        <source :src="`${episode.video_url}`" type="video/mp4">
                    </video>

    <!--                    <iframe v-if="episode.video_url"-->
    <!--                            class="rumble" width="w-fit" height="" :src="`${episode.video_url}`" frameborder="0" allowfullscreen>-->
    <!--                    </iframe>-->
                </div>

                <div v-if="episode.video_id" class="mb-6 bg-black w-full p-6">
                    <div class="block mb-2 uppercase font-bold text-xs"
                           for="name"
                    >
                        Episode Video
                    </div>
                                                <div
                                                    v-if="episode.video.upload_status === 'processing'"
                                                    class="text-center place-self-center font-semibold text-xl">Video processing...</div>
                    <video v-if="episode.video.upload_status !== 'processing'"
                           id="episodeEditPlayer"
                           :src="episode.video.cdn_endpoint+episode.video.cloud_folder+episode.video.folder+'/'+episode.video.file_name"
                           controls></video>
                </div>
            </div>

        </div>
    </div>



</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";

defineProps({
    show: Object,
    team: Object,
    episode: Object,
})

function back() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
    // window.history.back()
}

</script>
