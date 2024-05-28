<template>
  <div class="">
    <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-black">
      <h2 class="text-xl font-semibold">{{ name }}</h2>
      <ul class="pb-4">
        <li>Max File Size: <span class="text-orange-400">{{ maxSize }}</span></li>
        <li>File Types accepted: <span class="text-orange-400">{{ fileTypes }}</span></li>
      </ul>

      <file-pond
          name="image"
          ref="pond"
          label-idle="Click to choose file, or drag here..."
          :server="serverOptions"
          @init="filepondInitialized"
          :accepted-file-types="fileTypes"
          @processfile="handleProcessedFile"
          :max-file-size="maxSize"
      />
    </div>
  </div>

</template>

<script setup>
import { useNotificationStore } from '@/Stores/NotificationStore'
import vueFilePond from 'vue-filepond'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import { ref } from 'vue'

const notificationStore = useNotificationStore()

let props = defineProps({
  image: Object,
  name: String,
  metadataKey: String,
  metadataValue: String,
  modelType: String,
  modelId: String,
  maxSize: String,
  fileTypes: String,
})

const emit = defineEmits(['reloadImage', 'imageUploaded'])

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
)

// Define reactive variables
const myFiles = ref([{ source: 'cat.jpeg', options: { type: 'local' } }]);
const maxSize = ref('30MB');
const fileTypes = ref(['image/png', 'image/jpeg']);
const modelType = 'yourModelType'; // Replace with your actual model type
const modelId = 'yourModelId'; // Replace with your actual model ID

const serverOptions = ref({
  process: {
    url: `/upload?modelType=${props.modelType}&modelId=${props.modelId}`,
    method: 'POST',
    withCredentials: false,
    headers: {},
    onload: (response) => response.key,
    onerror: (response) => response.data,
  },
});


function filepondInitialized() {
  console.log('Filepond is ready!')
}

function handleProcessedFile(error, file) {
  if (error) {
    console.log('Filepond processed file')
    console.log(error)
    console.log(file)
    return
  }

  emit('reloadImage')
}


</script>