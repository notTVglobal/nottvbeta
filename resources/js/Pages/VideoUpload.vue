<template>
    <div>
        <Head title="Video Upload" />
        <div id="topDiv"></div>
        <div class="place-self-center flex flex-col gap-y-3">
            <div class="bg-white text-black p-5 mb-10">

                <header class="flex justify-between mb-3 border-b border-gray-800">
                    <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">

                        <div
                            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert"
                            v-if="props.message"
                        >
                                                <span class="font-medium">
                                                    {{props.message}}
                                                </span>
                        </div>

                        <div id="topDiv" class="flex flex-col lg:flex-row items-center">
                            <h1 class="text-3xl font-semibold text-center lg:text-left">Video Upload</h1>

                        </div>

                    </div>

                </header>




                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">



                        <progress max="100" :value="userStore.uploadPercentage" class="w-full mb-4" />


                        <form id="videoUploadForm" action="/videoupload" class="dropzone dropzoneFile border border-gray-400 rounded w-full px-2 py-2 mb-6">
<!--                            add input fields and a submit button to send data back to Laravel -->


                        </form>



                        <div v-if="done" class="p-2 mb-6">{{ done }}</div>







                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <div
                                class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
                            >
                                <div
                                    class="table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    <div class="table-row">
                                        <div scope="col" class="hidden md:table-cell min-w-[8rem] px-6 py-3">
                                            Filename
                                        </div>
                                        <div scope="col" class="table-cell px-6 py-3">
                                            ID
                                        </div>
                                        <div scope="col" class="hidden xl:table-cell px-6 py-3">
                                            Category
                                        </div>
                                        <div scope="col" class="hidden 2xl:table-cell px-6 py-3">
                                            Created On
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
                                            class="hidden md:table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                        >
                                            <button @click.prevent="videoPlayerStore.loadNewSourceFromFile(video)">{{ video.file_name }}</button>
                                        </div>
                                        <div
                                            scope="row"
                                            class="table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                        >
                                            <span>{{ video.id }}</span>
                                        </div>
                                        <div
                                            scope="row"
                                            class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                        >
                                            <span>{{ video.category }}</span>
                                        </div>
                                        <div class="hidden 2xl:table-cell px-6 py-4">
                                            <span >{{ formatDate(video.created_at) }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Paginator -->
                            <Pagination :links="videos.links" class="mt-6"/>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</template>

<script setup>
import Pagination from "@/Components/Pagination"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import { onMounted, ref } from "vue";
import { Dropzone } from "dropzone";
import { useForm } from "@inertiajs/inertia-vue3"
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'videoUpload'

let scrollToTopDivCounter = 0

function scrollToTopDiv() {
    if (scrollToTopDivCounter = 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        scrollToTopDivCounter ++;
    }
}
let uploadPercentage = ref(0);

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    scrollToTopDiv()

// see options for Dropzone here: https://github.com/dropzone/dropzone/blob/main/src/options.js
    let myDropzone = new Dropzone("#videoUploadForm", {
        url: "/videoupload",
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: '50 GB', // MB
        chunking: true,
        chunkSize: 2 * 1024 * 1024,
        parallelChunkUploads: false,
        retryChunks: false,
        retryChunksLimit: 10,
        acceptedFiles: 'video/mp4',
        capture: 'camera',
        uploadprogress: function(file, progress, bytesSent) {
            userStore.uploadPercentage = progress;
            console.log(userStore.uploadPercentage);
        },
        dictDefaultMessage: "Click here or Drop files here to upload",
        forceFallback: false, // for testing, set to true.
        accept: function(file, done) {
            if (file.name == "justinbieber.jpg") {
                done("Naha, you don't.");
            }
            else { done(); }
        }
    });

    myDropzone.on("addedfile", file => {
        console.log(`File added: ${file.name}`);
        onUploadProgress(file);

    });

    myDropzone.on("complete", function(file) {
        myDropzone.removeFile(file);
        userStore.uploadPercentage = 0;
        Inertia.reload({
            only: ["videos"],
        });
    });










})


function onUploadProgress( progressEvent ) {
    // this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ) );
}





let props = defineProps({
    filters: Object,
    can: Object,
    videos: Object,
    message: String,
    errors: ref(''),
    isHidden: ref(false),
    done: ref(),
});


// Dropzone tutorial: https://www.youtube.com/watch?v=wWKhKPN_Pmw

// let dropzoneFile = ref([]);
// const active = ref(false);
// const toggleActive = () => {
//     active.value = !active.value;
// }
// const drop = (e) => {
//     dropzoneFile.value = e.dataTransfer.files[0];
//     active.value = !active.value;
// }
// const selectedFile = () => {
//     dropzoneFile.value = document.querySelector('.dropzoneFile').files[0];
// }


let form = useForm({
    // name: '',
    // description: '',
    file: [],
    // file_url: '',
    // video: '',
});



</script>


<style scoped>

.dropzone {
    width: 400px;
    height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    row-gap: 16px;
    border: 2px dashed #6b7280;
    transition: 0.3s ease all;
}

label {
    padding: 8px 12px;
    color: #fff;
    background-color: #4bb1b1;
    transition: 0.3s ease all;
}

.active-dropzone {
    color: #fff;
    border-color: #fff;
    background-color: #4bb1b1;
}

.active-dropzone  label {
    background-color: #fff;
    color: #4bb1b1;
}


</style>
