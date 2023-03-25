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

defineProps({
    image: Object,
    name: String,
    server: String,
    maxSize: String,
    fileTypes: String,
})

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

FilePond.setOptions = ({
    fileMetadataObject: {
        show_id: '1',
    },
});

function filepondInitialized() {
    console.log("Filepond is ready!");
    console.log('Filepond object:', FilePond);
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
