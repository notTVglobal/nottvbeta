<template>
    <Head title="Image uploading"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <div class="grid grid-cols-1 grid-rows-2">
                    <h1 class="text-3xl font-semibold">Image Uploader</h1>
                </div>
                <span class="text-xs font-semibold text-red-700">Admin Mode</span>
                <div class="grid grid-cols-1 grid-rows-2">
                    <div class="justify-self-end mb-4">
                        <Link :href="`/dashboard`"><button
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                        >Dashboard</button>
                        </Link>
                    </div>
                </div>
            </div>

    <div class="max-w-lg mx-auto mt-2 bg-gray-200 p-6">
        <div class="pt-3 pb-4">
            <ul>
                <li>Max File Size: <span class="text-orange-400">20MB</span></li>
                <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
            </ul>
        </div>
        <file-pond
            name="image"
            ref="pond"
            label-idle="Click to choose image, or drag here..."
            @init="filepondInitialized"
            server="/upload"
            accepted-file-types="image/jpg, image/jpeg, image/png"
            @processfile="handleProcessedFile"
            allow-multiple="true" max-files="10"
            max-file-size="20MB"

        />

    </div>

    <div class="mt-8 mb-24 mx-auto">
        <h3 class="text-2xl font-medium text-center">Image Gallery</h3>
<!--        <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
        <!-- Paginator -->
        <Pagination :links="images.links" class="mt-6"/>
        <div class="grid grid-cols-3 gap-2 justify-evenly mt-4">
            <div v-for="image in images.data" :key="image.id">
                <img :src="'/storage/images/' + image.name">
            </div>
        </div>
        <!-- Paginator -->
        <Pagination :links="images.links" class="mt-6"/>
    </div>
        </div>
    </div>
</template>


<script setup>
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import Pagination from "@/Components/Pagination"
import {Head, Link} from '@inertiajs/inertia-vue3'
// import {ref, onMounted } from 'vue'
// import vueFilePond, { setOptions } from 'vue-filepond'
import "filepond/dist/filepond.min.css"
// import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    images: Object,
    // filters: Object,
});



// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('image', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

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
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import vueFilePond, { setOptions } from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import 'filepond/dist/filepond.min.css';
import {Inertia} from "@inertiajs/inertia";
import { onMounted } from "vue";

let videoPlayer = useVideoPlayerStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview
);

// The setOptions isn't working. It works in the Laracast Advanced Image Uploading Tutorial, but it isn't working here.
// let serverMessage = {};
// setOptions({
//     server: {
//         process: {
//             // onerror: (response) => {
//             //     serverMessage = JSON.parse(response);
//             // },
//             url: './upload',
//             headers: {
//                 'X-CSRF-TOKEN': '',
//             },
//         },
//     },
//     // labelFileProcessing: () => {
//     //     return serverMessage.error;
//     // }
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
