<template>
  <div class="">
    <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-black">
      <h2 class="text-xl font-semibold">{{ name }}</h2>
      <ul class="pb-4">
        <li>Max File Size: <span class="text-orange-400">{{ maxSize }}</span></li>
        <li>File Types accepted: <span class="text-orange-400">{{ fileTypes }}</span></li>
      </ul>

      <div class="checkbox-container mb-4">
        <input type="checkbox" id="removeExif" v-model="removeExif" class="checkbox checkbox-md">
        <label for="removeExif" class="checkbox-label">Remove EXIF data</label>
      </div>

      <file-pond
          name="image"
          ref="pond"
          class-name="my-pond"
          allow-multiple="false"
          label-idle="Click to choose file, or drag here..."
          :server="`/api/image-upload?modelType=${props.modelType}&modelId=${props.modelId}&removeExif=${removeExif.value}`"
          accepted-file-types="image/jpeg, image/png"
          @init="filepondInitialized"
          @processfile="handleProcessedFile"
          :max-file-size="maxSize"
          allowRemove="false"
          allowRevert="false"
      />
    </div>
  </div>

</template>

<script setup>
import vueFilePond from 'vue-filepond'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import { ref } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore  = useNotificationStore()

let props = defineProps({
  // image: Object,
  name: String,
  metadataKey: String,
  metadataValue: String,
  modelType: String,
  modelId: Number,
  maxSize: String,
  fileTypes: String,
})

const myFiles = ref([''])

const emit = defineEmits(['reloadImage', 'imageUploaded'])

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
)

// Define reactive variables
const removeExif = ref(false)
const maxSize = ref('30MB')
const fileTypes = ref(['image/png', 'image/jpeg'])
// const modelType = 'yourModelType'; // Replace with your actual model type
// const modelId = 'yourModelId'; // Replace with your actual model ID

// Get the CSRF token from the meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

const serverOptions = ref({
  process: {
    url: `/api/image-upload?modelType=${props.modelType}&modelId=${props.modelId}&removeExif=${removeExif.value}`,
    method: 'POST',
    withCredentials: false,
    // headers: {},
    onload: (response) => response,
    // onload: (response) => response.key,
    // onerror: (response) => response.data,
  },
})


function filepondInitialized() {
  console.log('Filepond is ready!')
}
//
// function handleFilePondInit() {
//   console.log('FilePond has initialized')
// }

function handleProcessedFile(error, file) {
  if (error) {
    console.log('FilePond processed file error:', error);
    return;
  }
  emit('reloadImage')

  // const response = JSON.parse(file.serverId)
  // emit('imageUploaded', response)
}


</script>

<style scoped>
.checkbox-container {
  display: flex;
  align-items: center; /* Align items vertically */
}

.checkbox {
  margin-right: 8px; /* Space between the checkbox and the label */
}
</style>