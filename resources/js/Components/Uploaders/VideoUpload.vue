<template>
    <div>

        <progress v-show="userStore.uploadPercentage != 0" max="100" :value="userStore.uploadPercentage" class="w-full mb-4" />

        <div v-show="uploadingMessage" class="mb-4 font-bold text-center">Please stay on this screen until upload is complete.</div>
        <form id="videoUploadForm" action="/videoupload" class="dropzone dropzoneFile border border-gray-400 rounded w-full h-48 max-w-md px-2 py-2 mb-6">
            <!--                            add input fields and a submit button to send data back to Laravel -->


        </form>

    </div>
</template>

<script setup>
import { Dropzone } from "dropzone";
import { useForm } from "@inertiajs/inertia-vue3"
import { useUserStore } from "@/Stores/UserStore";
import { onMounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

let userStore = useUserStore()
let uploadPercentage = ref(0);
let uploadingMessage = ref(0);

onMounted(() => {
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
    capture: null,
    // can set the capture method as camera, microphone or video
    // for mobile devices to skip the file selection and choose the
    // recording device instead.
    acceptedFiles: 'video/*, audio/*',
    uploadprogress: function(file, progress, bytesSent) {
        userStore.uploadPercentage = progress;
        console.log(userStore.uploadPercentage);
    },
    dictDefaultMessage: "Click here or Drop files here to upload",
    forceFallback: false, // for testing, set to true.
    accept: function(file, done) {
        if (file.name == "") {
            done("Need a file.");
        }
        else { done(); }
    }
});

myDropzone.on("addedfile", file => {
    uploadingMessage = 1;
    console.log(`File added: ${file.name}`);

});

myDropzone.on("complete", function(file) {
    uploadingMessage = 0;
    myDropzone.removeFile(file);
    userStore.uploadPercentage = 0;
    Inertia.reload({
        only: ["videos"],
    });
});

})

let form = useForm({
    file: [],
});

// let props = defineProps({
//     filters: Object,
//     can: Object,
//     videos: Object,
//     message: String,
//     errors: ref(''),
//     isHidden: ref(false),
//     done: ref(),
// });

</script>
<style scoped>

.dropzone {
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
