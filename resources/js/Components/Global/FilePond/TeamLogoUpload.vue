<template>
  <div>

    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
           for="name"
    >
      Change Team Logo
    </label>
    <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">
      <h2 class="text-xl font-semibold">Upload Team Logo</h2>

      <ul class="pb-4">
        <li>Max File Size: <span class="text-orange-400">10MB</span></li>
        <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
      </ul>
      <file-pond
          name="image"
          ref="pond"
          label-idle="Click to choose image, or drag here..."
          @init="filepondInitialized"
          server="/upload"
          accepted-file-types="image/jpg, image/jpeg, image/png"
          @processfile="handleProcessedFile"
          max-file-size="10MB"
      />
    </div>

  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from 'filepond-plugin-file-metadata'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import { useTeamStore } from "@/Stores/TeamStore"

const teamStore = useTeamStore()

const props = defineProps({
  team: Object,
  images: Object,
});

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

function filepondInitialized() {
  console.log("Filepond is ready!");
  console.log('Filepond object:', FilePond);
}

function handleProcessedFile(error, file) {
  if (error) {
    console.log("Filepond processed file");
    console.log(error);
    console.log(file);
    return;
  }

  // add the file to our images array

  Inertia.reload({
    only: ['images'],
  });

  // tec21: this works, but is a 1-ms delay
  // the best way? if the database is delayed
  // longer than 1-ms will the image still load
  // on the page?

  setTimeout(function () {
    teamStore.logoName = props.images.data[0].name;
    teamStore.logoId = props.images.data[0].id;
    console.log("wait 1 milisecond");
  }, 100);

}

</script>
