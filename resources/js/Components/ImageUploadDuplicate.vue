<template>

  <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
    <div class="bg-white text-black p-5 mb-10">

      <div class="max-w-lg mx-auto mt-2 bg-gray-200 p-6">
        <h1 class="text-2xl font-bold text-center mb-4">Upload Image</h1>

        <file-pond
            name="image"
            ref="pond"
            label-idle="Click to choose image, or drag here..."
            server="/upload"
            @init="filepondInitialized"
            accepted-file-types="image/jpg, image/jpeg, image/png"
            @processfile="handleProcessedFile"
            allow-multiple="true" max-files="10"
            max-file-size="20MB"
        />
        <div class="pt-3 pb-4">
          <ul>
            <li>Max File Size: <span class="text-orange-400">20MB</span></li>
            <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</template>


<script setup>
import { router } from '@inertiajs/vue3'
import "filepond/dist/filepond.min.css"

let props = defineProps({
  images: Object
});


// config: { headers: function () { return {} } }


// const FilePond = vueFilePond(FilePondPluginFileValidateType);
// const pond = ref([]);
// const FilePondInitialized = ref();
// console.log(FilePondInitialized, "Filepond is ready!");
// console.log("Filepond object:", pond);
//
// onMounted(() => setOptions({
//     server: {
//         process: {
//             url: './upload',
//             headers: {
//                 "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
//             }
//         }
//     }
// }))

</script>

<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
// import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond/dist/filepond.min.css';
import { router } from '@inertiajs/vue3';

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
    // this is a plugin that needs to be installed:
    // FilePondPluginImagePreview
);

// The setOptions isn't working. It works in the Laracast Advanced Image Uploading Tutorial, but it isn't working here.
// let serverMessage = {};
// setOptions({
//     server: {
//         process: {
//             onerror: (response) => {
//                 serverMessage = JSON.parse(response);
//             },
//         }
//     },
//     labelFileProcessing: () => {
//         return serverMessage.error;
//     }
// });

export default {
  components: {
    FilePond
  },
  methods: {

    filepondInitialized: function () {
      console.log("Filepond is ready!");
      console.log('Filepond object:', this.$refs.pond);

    },
    handleProcessedFile(error, file) {
      if (error) {
        console.log("Filepond processed file");
        console.log(error);
        console.log(file);
        return;
      }

      // add the file to our images array
      router.reload({
        only: ["images"],
      });

    }
  },
  props: {
    images: Object
  },
  setup(props) {

  }

  // filepondProcessFile = (error, file) {
  //     console.log("Filepond processed file");
  //     console.log(error);
  //     console.log(file);
  //
  //     router.reload({
  //         only: ["images"],
  //     });
  // };


};
</script>
