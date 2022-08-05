<template>
    <Head title="Image uploading"/>

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

    <div class="max-w-lg mx-auto mt-24">
        <h1 class="text-4xl font-bold text-center">Image Uploader</h1>
        <p class="pt-3 pb-4 text-orange-400">
            This uploader is not currently functioning.
        </p>
        <file-pond
            name="image"
            ref={{pond}}
            label-idle="Click to choose image, or drag here..."
            @init="FilePondInitialized"
            accepted-file-types="image/*"
        />
    </div>
    <div class="pt-10 pb-10">
    </div>
        </div>
    </div>
</template>


<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import {ref} from 'vue';
import vueFilePond, {setOptions} from 'vue-filepond';
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false
//
// config: { headers: function () { return {} } },
// const props = defineProps({
//             process: {
//                 url: './upload',
//                 headers: {
//                     'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf_token"]').content
//                 }
//             }
// });


const FilePond = vueFilePond(FilePondPluginFileValidateType);
const pond = ref([]);
const FilePondInitialized = ref();
console.log(FilePondInitialized, "Filepond is ready!");
console.log("Filepond object:", pond);

</script>

<!--<script>-->
<!--import vueFilePond, { setOptions } from "vue-filepond";-->
<!--import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";-->
<!--import "filepond/dist/filepond.min.css";-->
<!--import { ref } from "vue";-->
<!--import { Inertia } from "@inertiajs/inertia";-->

<!--const FilePond = vueFilePond(FilePondPluginFileValidateType);-->

<!--// setOptions({-->
<!--//     server: {-->
<!--//         process: {-->
<!--//             url: './upload',-->
<!--//             headers: {-->
<!--//                 "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')-->
<!--//                     .content,-->
<!--//             },-->
<!--//         },-->
<!--//     },-->
<!--// });-->

<!--export default {-->
<!--    components: {-->
<!--        Head,-->
<!--        Link,-->
<!--        FilePond,-->
<!--    },-->
<!--    props: {-->
<!--        images: Object,-->
<!--    },-->
<!--    setup() {-->
<!--        const pond = ref(null);-->

<!--        const filepondInitialized = () => {-->
<!--            console.log("Filepond initialized");-->

<!--            // console log filepond object-->
<!--            console.log(pond.value);-->
<!--        };-->

<!--        const filepondProcessFile = (error, file) => {-->
<!--            console.log("Filepond processed file");-->
<!--            console.log(error);-->
<!--            console.log(file);-->

<!--            Inertia.reload({-->
<!--                only: ["images"],-->
<!--            });-->
<!--        };-->

<!--        return {-->
<!--            pond,-->
<!--            filepondInitialized,-->
<!--            filepondProcessFile,-->
<!--        };-->
<!--    },-->
<!--};-->
<!--</script>-->
