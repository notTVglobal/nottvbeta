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
            :server="server"
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
import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import {Inertia} from "@inertiajs/inertia";
import {computed, ref} from "vue";

let props = defineProps({
    image: Object,
    name: String,
    metadataKey: String,
    metadataValue: String,
    server: String,
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
        // Construct a FormData object to send the file and metadata to your Laravel server
        const formData = new FormData();
        formData.append('file', file, file.name); // Add the file
        formData.append('metadata', JSON.stringify(customMetadata)); // Add the metadata as a JSON string

        // Make an Axios POST request to your Laravel backend
        axios.post(props.server, formData, {
            headers: {
                'Content-Type': 'multipart/form-data', // Set the appropriate content type
            },
            onUploadProgress: (event) => {
                const progressPercentage = Math.round((event.loaded / event.total) * 100);
                // Update the progress bar or display progress percentage if needed
                progress(progressPercentage);
            },
        })
            .then((response) => {
                // Handle a successful upload
                load(response.data.url); // Pass the file URL to the load() function
            })
            .catch((err) => {
                // Handle upload error
                console.error('Upload error:', err);
                error('Error uploading the file'); // Pass an error message to the error() function
            });
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
