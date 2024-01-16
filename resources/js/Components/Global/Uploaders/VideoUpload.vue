<template>
  <div>

    <progress v-show="userStore.uploadPercentage != 0" max="100" :value="userStore.uploadPercentage" class="w-full"/>
    <div v-show="userStore.uploadPercentage != 0" class="w-full mb-4">{{ userStore.uploadPercentageRounded }}%</div>

    <div v-show="uploadingMessage" class="mb-4 font-bold text-center">Please stay on this screen until upload is
      complete.
    </div>
    <div v-show="uploadCompleteMessage" class="mb-4 font-bold text-center">Upload is complete. The video is now
      processing.
    </div>
    <form v-show="!isHidden" id="videoUploadForm" action="/videoupload"
          class="dropzone dropzoneFile border border-black rounded w-full h-48 max-w-md px-2 py-2 mb-6">
      <!--                            add input fields and a submit button to send data back to Laravel -->
      <input hidden name="movieId" v-model="form.movieId">
      <!--            <input hidden name="movieTrailerId" v-model="form.movieTrailerId">-->
      <input hidden name="showEpisodeId" v-model="form.showEpisodeId">
    </form>

  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { onMounted, ref } from "vue"
import { useForm } from "@inertiajs/inertia-vue3"
import { Dropzone } from "dropzone"
import { useUserStore } from "@/Stores/UserStore"

const userStore = useUserStore()
let uploadPercentage = ref(0)
let uploadingMessage = ref(0)
let uploadCompleteMessage = ref(0)
let isHidden = ref(false)

onMounted(() => {
  // Make sure the element with the ID "videoUploadForm" is available in the DOM.
  const videoUploadForm = document.getElementById('videoUploadForm');

  if (videoUploadForm) {
    // Initialize Dropzone on the element.

    // see options for Dropzone here: https://github.com/dropzone/dropzone/blob/main/src/options.js
    let myDropzone = new Dropzone("#videoUploadForm", {
      url: "/videoupload",
      paramName: "file", // The name that will be used to transfer the file
      maxFilesize: '25 GB', // MB
      chunking: true,
      chunkSize: 2 * 1024 * 1024,
      parallelChunkUploads: false,
      retryChunks: true,
      retryChunksLimit: 10,
      capture: null,
      // can set the capture method as camera, microphone or video
      // for mobile devices to skip the file selection and choose the
      // recording device instead.
      acceptedFiles: 'video/*, audio/*',
      uploadprogress: function (file, progress, bytesSent) {
        userStore.uploadPercentage = progress;
        console.log(userStore.uploadPercentage);
        if (userStore.uploadPercentage !== 100) {
          isHidden = true;
        }
      },
      dictDefaultMessage: "Click here or Drop video here to upload <br>(Max video file size is 25GB)",
      forceFallback: false, // for testing, set to true.
      accept: function (file, done) {
        if (file.name === "") {
          done("Need a file.");
        } else if (file.size > 25000000000) {
          console.log(file.size)
          done("Video file too big.");
          alert('Video file must be smaller than 25GB');
        } else {
          done();
        }
      }
    });

    myDropzone.on("addedfile", file => {
      uploadingMessage = 1;
      console.log(`File added: ${file.name}`);

    });

    myDropzone.on("complete", function (file) {
      uploadingMessage = 0;
      uploadCompleteMessage = 1;
      myDropzone.removeFile(file);
      userStore.uploadPercentage = 0;
      isHidden = false;
      Inertia.reload({
        only: ["videos"],
      });
    });

  } else {
    console.error('Element with ID "videoUploadForm" not found in the DOM.');
  }
})

let props = defineProps({
  movieId: Number,
  movieTrailerId: Number,
  showEpisodeId: Number,
})

function setMovieOrEpisodeId() {
  if (props.movieId !== null) {
    userStore.uploadMovieId = props.mov;
  } else if (props.movieTrailerId !== null) {
    userStore.movieTrailerId = props.movieTrailerId;
  } else if (props.showEpisodeId !== null) {
    userStore.uploadShowEpisodeId = props.showEpisodeId;
  }
}

setMovieOrEpisodeId()

let form = useForm({
  file: [],
  // movieId: userStore.uploadMovieId,
  movieId: props.movieId,
  movieTrailerId: props.movieId,
  showEpisodeId: props.showEpisodeId,
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
  border: 2px dashed #000000;
  background-color: #fce4bb;
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

.active-dropzone label {
  background-color: #fff;
  color: #4bb1b1;
}

/*6b7280*/
/*4bb1b1*/

</style>
