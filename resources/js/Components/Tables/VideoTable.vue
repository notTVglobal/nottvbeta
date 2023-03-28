<template>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div
            class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
        >
            <div
                class="table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
            >
                <div class="table-row">
                    <div scope="col" class="table-cell px-6 py-3">
                        <font-awesome-icon icon="fa-repeat" class="mr-2 cursor-pointer hover:text-blue-500" @click.prevent="reload()"/>
                        Filename
                    </div>
                    <div v-if="can.viewAny" scope="col" class="table-cell px-6 py-3">
                        Creator
                    </div>
                    <div scope="col" class="hidden md:table-cell px-6 py-3">
                        ID
                    </div>
                    <div scope="col" class="hidden xl:table-cell px-6 py-3">
                        Type
                    </div>
                    <div scope="col" class="hidden xl:table-cell px-6 py-3">
                        Size
                    </div>
                    <div scope="col" class="hidden 2xl:table-cell px-6 py-3">
                        Created On
                    </div>
                    <div scope="col" class="px-6 py-3">

                    </div>
                </div>
            </div>
            <div class="table-row-group">
                <div
                    v-for="video in videos.data"
                    :key="video.id"
                    class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                >
                    <div
                        scope="row"
                        class="table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                    >
                        <button
                                v-if="video.can.view"
                                @click.prevent="videoPlayerStore.loadNewSourceFromFile(video)"
                                :disabled="video.upload_status === 'processing'"
                                class="disabled:cursor-not-allowed disabled:text-gray-500 disabled:italic inline">{{ video.file_name }}</button>
                        <span v-if="!video.can.view" class="font-semibold text-red-700">You are currently unable to view this video. Please check with the admin.</span>
                        <div v-show="video.upload_status === 'processing'" class="ml-2 mb-1 py-1 px-2 bg-gray-600 text-gray-50 text-xs w-fit rounded-lg inline">Processing</div>
                    </div>
                    <div
                        v-if="can.viewAny"
                        scope="row"
                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                    >
                        <span>{{ video.user_id }}</span>
                    </div>
                    <div
                        scope="row"
                        class="hidden md:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                    >
                        <span>{{ video.id }}</span>
                    </div>
                    <div
                        scope="row"
                        class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                    >
                        <span>{{ video.type }}</span>
                    </div>
                    <div
                        scope="row"
                        class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                    >
                        <span>{{ video.size }}</span>
                    </div>
                    <div class="hidden 2xl:table-cell px-6 py-4">
                        <span>{{ formatDate(video.created_at) }}</span>
                    </div>
                    <div>
                        <Link :href="`#`"><button
                            @click.prevent="deleteVideo(video)"
                            class="px-3 py-2 mb-2 mr-4 font-xs text-white bg-red-600 hover:bg-red-500 rounded-lg"
                        >Delete</button>
                        </Link>
                    </div>

                </div>
            </div>
        </div>
        <!-- Paginator -->
        <Pagination :data="videos" class="pb-6"/>
    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination.vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";
import {Inertia} from "@inertiajs/inertia";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

let videoPlayerStore = useVideoPlayerStore()

defineProps({
    videos: Object,
    can: Object,
})

function reload() {
    Inertia.reload({
        only: ['videos'],
    });
}
function deleteVideo($video) {
    if(confirm('Are you sure you want to delete this video? This action is not reversible and may have' +
        ' devastating effects on the database.')) {
        Inertia.post('/video/delete', {'videoId': $video.id});
    }
}

</script>
