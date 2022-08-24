<template>
    <Head title="Image uploading"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

    <div class="max-w-lg mx-auto mt-24">
        <h1 class="text-4xl font-bold text-center">Image Uploader</h1>
        <p class="pt-3 pb-4 text-orange-400">
            This uploader is not currently functioning.
        </p>
        <file-pond
            name="image"
            ref="pond"
            label-idle="Click to choose image, or drag here..."
            server="upload"
            @init="filepondInitialized"
            accepted-file-types="image/*"
        />
    </div>
    <div class="mt-8 mb-24 mx-auto">
        <h3 class="text-2xl font-medium text-center">Image Gallery</h3>
        <div class="grid grid-cols-3 gap-2 justify-evenly mt-4">
            <div v-for="(image, index) in images" :key="index">
                <img :src="'/storage/images/' + image">
            </div>
        </div>
        Hello?
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
// import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond/dist/filepond.min.css';

// const FilePond = vueFilePond();
const FilePond = vueFilePond(
    FilePondPluginFileValidateType
    // this is a plugin that needs to be installed:
    // FilePondPluginImagePreview
);

// we have a CORS exception for the /upload route.. if we want more security than we need to remove the
// CORS exception and implement this function.. however, I couldn't get it to work (tec21).
// setOptions({
//     server: {
//         process: {
//             url: './upload',
//             headers: {
//                 "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
//             }
//         }
//     }
// });



export default {
    components: {
        FilePond
    },
    data() {
        return {
            images: []
        }
    },
    mounted() {
        axios.get('/images')
            .then((response) => {
                this.images = response.data;
            })
            .catch((error) => {
                console.error(error);
            });
    },
    methods: {

        filepondInitialized: function () {
            console.log("Filepond is ready!");
            console.log('Filepond object:', this.$refs.pond);

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


    },
};
</script>
