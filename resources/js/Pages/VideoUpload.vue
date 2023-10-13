<template>
    <div>
        <Head title="Video Upload" />
        <div class="place-self-center flex flex-col gap-y-3">
            <div id="topDiv" class="bg-white text-black p-5 mb-10">

                <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

                <div id="topDiv"></div>
                <header class="flex justify-between mb-3 border-b border-gray-800 pb-6">

                        <h1 class="text-3xl font-semibold text-center lg:text-left">Video Upload</h1>


                    <div>
                        <button
                            @click="back"
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Back
                        </button>

                    </div>

                </header>


                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div class="flex justify-between">
                            <VideoUpload />
                            <!-- doesn't work .. breaks the CSS -->
                            <!--    <MobileVideoRecord />-->
                            <div class="flex-wrap ml-2 px-5 pb-6">
                                <div class="">My total storage used: {{ myTotalStorageUsed }}</div>
                                <div class="">not.TV total storage used: {{ notTvTotalStorageUsed }}</div>
                            </div>
                        </div>


                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <VideoTable :videos="videos" :can="can"  class=""/>
                        </div>
                        <div v-if="can.viewAny" class="mt-6 p-2 rounded-xl bg-gray-300 border-2 border-gray-500">



                            <div class="flex justify-between mt-6 lg:mt-0">
                                <div class="p-2 font-semibold text-xl">All Videos (Admin view only)</div>
                                <div class="relative">
                                    <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                                        focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
                                    <div class="absolute top-0 flex items-center h-full ml-2">
                                        <svg class="fill-current text-gray-400 w-4 pb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/></svg>

                                    </div>
                                </div>
                            </div>
                            <VideoTable :videos="allVideos" :can="can" class=""/>
                        </div>
                    </div>
                </div>

FOOTER

            </div>
        </div>
    </div>
</template>

<script setup>
import {defineAsyncComponent, onBeforeMount, onMounted, ref, watch} from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { Dropzone } from "dropzone"
import { useForm } from "@inertiajs/inertia-vue3"
import Pagination from "@/Components/Pagination"
import VideoTable from "@/Components/Tables/VideoTable"

// import VideoUpload from "@/Components/Uploaders/VideoUpload"
const VideoUpload = defineAsyncComponent(() =>
    import('@/Components/Uploaders/VideoUpload')
)
import MobileVideoRecord from "@/Components/Uploaders/MobileVideoRecord"
import throttle from "lodash/throttle";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'videoUpload'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

// see options for Dropzone here: https://github.com/dropzone/dropzone/blob/main/src/options.js
//     let myDropzone = new Dropzone("#videoUploadForm", {
//         url: "/videoupload",
//         paramName: "file", // The name that will be used to transfer the file
//         maxFilesize: '50 GB', // MB
//         chunking: true,
//         chunkSize: 2 * 1024 * 1024,
//         parallelChunkUploads: false,
//         retryChunks: false,
//         retryChunksLimit: 10,
//         acceptedFiles: 'video/*, audio/*',
//         uploadprogress: function(file, progress, bytesSent) {
//             userStore.uploadPercentage = progress;
//             console.log(userStore.uploadPercentage);
//         },
//         dictDefaultMessage: "Click here or Drop files here to upload",
//         forceFallback: false, // for testing, set to true.
//         accept: function(file, done) {
//             if (file.name == "justinbieber.jpg") {
//                 done("Naha, you don't.");
//             }
//             else { done(); }
//         }
//     });
//
//     myDropzone.on("addedfile", file => {
//         console.log(`File added: ${file.name}`);
//
//     });
//
//     myDropzone.on("complete", function(file) {
//         myDropzone.removeFile(file);
//         userStore.uploadPercentage = 0;
//         Inertia.reload({
//             only: ["videos"],
//         });
//     });



let props = defineProps({
    filters: Object,
    can: Object,
    videos: Object,
    allVideos: Object,
    myTotalStorageUsed: String,
    notTvTotalStorageUsed: String,
    errors: ref(''),
    isHidden: ref(false),
    // done: ref(),
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/videoupload', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));


// let form = useForm({
//     file: [],
// });

function back() {
    window.history.back()
}

</script>


<style scoped>

/*.dropzone {*/
/*    display: flex;*/
/*    flex-direction: column;*/
/*    justify-content: center;*/
/*    align-items: center;*/
/*    row-gap: 16px;*/
/*    border: 2px dashed #6b7280;*/
/*    transition: 0.3s ease all;*/
/*}*/

/*label {*/
/*    padding: 8px 12px;*/
/*    color: #fff;*/
/*    background-color: #4bb1b1;*/
/*    transition: 0.3s ease all;*/
/*}*/

/*.active-dropzone {*/
/*    color: #fff;*/
/*    border-color: #fff;*/
/*    background-color: #4bb1b1;*/
/*}*/

/*.active-dropzone  label {*/
/*    background-color: #fff;*/
/*    color: #4bb1b1;*/
/*}*/


</style>
