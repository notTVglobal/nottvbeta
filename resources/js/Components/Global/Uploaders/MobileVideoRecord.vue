<template>
  <div>

    <!--        <progress v-show="userStore.uploadPercentage != 0" max="100" :value="userStore.uploadPercentage" class="w-full mb-4" />-->

    <label>Record video from a mobile device</label>
    <form id="mobileVideoUploadForm" action="/videoupload"
          class="dropzone dropzoneFile border border-gray-400 rounded w-32 h-32 rounded-full bg-red-500 max-w-md px-2 py-2 mb-6">
      <!--                            add input fields and a submit button to send data back to Laravel -->


    </form>

  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from "vue"
import { Dropzone } from "dropzone"
import { useForm } from "@inertiajs/vue3"
import { useUserStore } from "@/Stores/UserStore"

const userStore = useUserStore()

onMounted(() => {
// see options for Dropzone here: https://github.com/dropzone/dropzone/blob/main/src/options.js
  let myDropzoneMobile = new Dropzone("#mobielVideoUploadForm", {
    url: "/videoupload",
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: '50 GB', // MB
    chunking: true,
    chunkSize: 2 * 1024 * 1024,
    parallelChunkUploads: false,
    retryChunks: false,
    retryChunksLimit: 10,
    capture: 'camcorder',
    acceptedFiles: 'video/*, audio/*',
    uploadprogress: function (file, progress, bytesSent) {
      userStore.uploadPercentage = progress;
      console.log(userStore.uploadPercentage);
    },
    dictDefaultMessage: "Click here",
    forceFallback: false, // for testing, set to true.
    accept: function (file, done) {
      if (file.name == "") {
        done("Need a file.");
      } else {
        done();
      }
    }
  });

  myDropzoneMobile.on("addedfile", file => {
    console.log(`File added: ${file.name}`);

  });

  myDropzoneMobile.on("complete", function (file) {
    myDropzoneMobile.removeFile(file);
    userStore.uploadPercentage = 0;
    // router.reload({
    //     only: ["videos"],
    // });
  });

})

let form = useForm({
  file: [],
});


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
