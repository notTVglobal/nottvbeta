

<template>
    <Head title="Image uploading"/>
    <div class="max-w-lg mx-auto mt-24">
        <h1 class="text-4xl font-bold text-center">Image Uploader</h1>
        <Link :href="('/dashboard')">Dashboard</Link>
        <file-pond
            name="image"
            ref={{pond}}
            label-idle="Click to choose image, or drag here..."
            @init="FilePondInitialized"
            accepted-file-types="image/*"
            />
    </div>
    <div>
<!--        <video width="320" height="240" controls>-->
<!--&lt;!&ndash;            <source src="https://cloudflarestream.com/91dc50d14c619364b534537fc95b45b4/manifest/manifest.m3u8" type="application/x-mpegURL">&ndash;&gt;-->
<!--&lt;!&ndash;            <source src="http://mist.nottv.io:8080/liberty_april_02.mp4" type="video/mp4">&ndash;&gt;-->
<!--            <source src="http://mist.nottv.io:8080/hls/liberty_april_02/index.m3u8" type="application/x-mpegURL">-->


<!--        </video>-->
<!--        <video :as="videoPlayer" class="video-js"></video>-->
<!--        <video id='hls-example'  class="video-js vjs-default-skin" width="400" height="300" controls>-->
<!--            <source type="application/x-mpegURL" src="http://www.streambox.fr/playlists/test_001/stream.m3u8">-->
<!--        </video>-->
    </div>
</template>


<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import vueFilePond, { setOptions } from 'vue-filepond';
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
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
<!--import { Head, Link } from "@inertiajs/inertia-vue3";-->
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
