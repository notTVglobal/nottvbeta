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
          @init="filepondInitialized"
          :server="serverOptions"
          :accepted-file-types="fileTypes"
          @processfile="handleProcessedFile"
          :max-file-size="maxSize"
      />
      <!--        <file-pond-->
      <!--            ref="filePond"-->
      <!--            :files="files"-->
      <!--            :server="serverOptions"-->
      <!--            @init="handleFilePondInit"-->
      <!--            @beforeaddfile="handleBeforeAddFile"-->
      <!--            @processfile="handleProcessedFile"-->
      <!--        />-->
    </div>
  </div>

</template>

<script setup>
import { useNotificationStore } from '@/Stores/NotificationStore'
import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'

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


// // Initialize FilePond with the File Metadata plugin
// const filePondOptions = {
//     allowMultiple: false,
//     labelIdle: "Drop files here or click to upload",
//     plugins: [FilePondPluginFileMetadata()],
// };


// Files array to store uploaded files
// const files = ref([]);

const metadataKey = props.metadataKey
const metadataValue = props.metadataValue
const customMetadata = {
  [metadataKey]: metadataValue
};
// customMetadata.value[props.metadataKey] = 'bar';
// customMetadata.value[props.metadataKey] = props.metadataValue;
// const customMetadata = ref({ [props.metadataKey]: props.metadataValue });
// const customMetadata = computed(() => ({
//     [props.metadataKey]: props.metadataValue,
// }));

// FilePond server options (you can customize this)
const serverOptions = {
  process: (fieldName, file, metadata, load, error, progress, abort) => {
    const formData = new FormData();
    formData.append('image', file, file.name); // Attach the file
    // Append modelType and modelId if it's part of your component
    if (props.modelType) {
      formData.append('modelType', props.modelType);
    }
    if (props.modelId) {
      formData.append('modelId', props.modelId);
    }

    // Axios request configuration
    const config = {
      onUploadProgress: (event) => {
        // Calculate and update progress
        progress(event.lengthComputable, event.loaded, event.total);
      },
    };

    // Perform the Axios POST request
    axios.post('/api/image-upload', formData, config)
        .then(response => {
          // FilePond expects the server to return a file ID on successful upload,
          // but you can adjust this based on your response structure.
          load(response.data.fileId);
        })
        .catch(err => {
          // Default error message and title in case err.response is undefined
          let errorMessage = 'An unexpected error occurred during the upload. Please try again.';
          let errorTitle = 'Upload Error';

          // Check if the error response exists and has data
          if (err.response && err.response.data) {
            // Example: Extracting error message from server response
            // Adjust based on your server's error response structure
            if (typeof err.response.data === 'object' && err.response.data.errors) {
              // If the errors are in object format, you might want to convert them to a string
              const errors = err.response.data.errors;
              const errorMessages = Object.keys(errors).map(key => `${key}: ${errors[key].join(', ')}`).join('\n');
              errorMessage = `Upload failed with errors:\n${errorMessages}`;
            } else if (typeof err.response.data === 'string') {
              // Directly use the error string as the message
              errorMessage = err.response.data;
            }
          }

          // Use the errorMessage and errorTitle in your notification
          notificationStore.setGeneralServiceNotification(errorTitle, errorMessage);

          // Pass a generic error message to FilePond's error handler to avoid displaying sensitive or detailed information directly in the UI
          error('An error occurred during the upload.');
        });


    // Return an abort function to stop the upload if needed
    return {
      abort: () => {
        // This function is called if the user aborts the upload
        console.log("Upload aborted by the user.");
        abort();
      }
    };
  },
};


// Initialize FilePond
// const handleFilePondInit = () => {
//     // FilePond has been initialized
//     console.log("FilePond is ready");
// };

// Handle the beforeaddfile event
// const handleBeforeAddFile = (file) => {
//     // Add metadata to the file object
//     file.setMetadata("key", "value");
//     // You can set multiple metadata fields as needed
//     // file.setMetadata("anotherKey", "anotherValue");
// };


const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

// Handle the beforeaddfile event
// const handleBeforeAddFile = (file) => {
//     // Add metadata to the file object
//     file.setMetadata("show_id", "1");
//     // You can set multiple metadata fields as needed
//     // file.setMetadata("anotherKey", "anotherValue");
// };

// FilePond.registerPlugin(FilePondPluginFileMetadata);
FilePond.setOptions = ({
  fileMetadataObject: {
    show_id: '1',
  },
});


// Initialize FilePond with the File Metadata plugin
// const filePondOptions = {
//     allowMultiple: true,
//     labelIdle: "Drop files here or click to upload",
//     plugins: [FilePondPluginFileMetadata()],
// };

function filepondInitialized() {
  console.log("Filepond is ready!");
  // console.log('Filepond object:', FilePond);

}

const emit = defineEmits(['reloadImage'])

function handleProcessedFile(error, file) {
  if (error) {
    console.log("Filepond processed file");
    console.log(error);
    console.log(file);
    return;
  }

  emit('reloadImage')

  // Inertia.reload({
  //     only: ['image'],
  // });
}

</script>
