<template>
    <Head title="Image uploading"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

    <div class="max-w-lg mx-auto mt-2">
        <h1 class="text-4xl font-bold text-center mb-4">Image Uploader</h1>
        <p class="pt-3 pb-4">
            <ul>
                <li>Max File Size: <span class="text-orange-400">20MB</span></li>
                <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
            </ul>
        </p>
        <file-pond
            name="image"
            ref="pond"
            label-idle="Click to choose image, or drag here..."
            server="upload"
            @init="filepondInitialized"
            accepted-file-types="image/jpg, image/jpeg, image/png"
            @processfile="handleProcessedFile"
            allow-multiple="true" max-files="10"
            max-file-size="20MB"
        />
    </div>
    <div class="mt-8 mb-24 mx-auto">
        <h3 class="text-2xl font-medium text-center">Image Gallery</h3>
        <div class="grid grid-cols-3 gap-2 justify-evenly mt-4">
            <div v-for="(image, index) in images" :key="index">
                <img :src="'/storage/images/' + image.name">
            </div>
        </div>
    </div>
        </div>
    </div>
</template>


<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3'
// import {ref, onMounted } from 'vue'
// import vueFilePond, { setOptions } from 'vue-filepond'
import "filepond/dist/filepond.min.css"
// import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

// let props = defineProps({
//     images: Object,
// });

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

</script>

<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
// import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond/dist/filepond.min.css';
import {Inertia} from "@inertiajs/inertia";

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
            Inertia.reload({
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
        //     Inertia.reload({
        //         only: ["images"],
        //     });
        // };


};
</script>
