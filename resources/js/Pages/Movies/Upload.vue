<template>

    <Head :title="`Upload Movie`"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>


    <header class="md:pageWidth pageWidthSmall">

        <Message v-if="showMessage" @close="showMessage = false"/>

        <div class="flex justify-between p-4 m-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
             role="alert"
             v-if="props.errors.video">
                <span class="font-medium">
                    {{ props.errors.video }}
                </span>
        </div>

    </header>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <label for="videoUpload" class="font-semibold block">Movie Upload</label>
                    <input
                        v-model="form.name"
                        type="text"
                        name="name"
                        id="name"
                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"
                        placeholder="Name"
                    />
                    <div v-if="form.errors.name" v-text="form.errors.name"
                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                    <textarea
                        v-model="form.description"
                        type="text"
                        name="description"
                        id="description"
                        cols="30"
                        row="10"
                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"
                        placeholder="Description"
                    />
                    <div v-if="form.errors.description" v-text="form.errors.description"
                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

                    <input
                        type="file"
                        name="video"
                        id="video"
                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"
                        ref="fileInput"
                        accept="video/*"
                        @input="form.video = $event.target.files[0]"
                        @change="selectFile"/>
                    <div v-if="form.errors.video" v-text="form.errors.video"
                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

                    <button
                        @click="submit"
                        class="bg-green-600 hover:bg-green-500 text-white rounded py-2 px-4"
                        :disabled="form.processing"
                    >
                        Upload
                    </button>

                </form>
            </div>


        </div>
    </div>

</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import Message from "@/Components/Modals/Messages"
import { ref, onMounted } from "vue"
import {useForm} from "@inertiajs/inertia-vue3"
import TabbableTextarea from "@/Components/TabbableTextarea"

import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"

import vueFilePond, { setOptions } from 'vue-filepond'
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileMetadata from "filepond-plugin-file-metadata"
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'


let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});



// let props = defineProps({
//     user: Object,
//     show: Object,
//     team: Object,
//     poster: String,
// });
let showMessage = ref(true);


let props = defineProps({
    message: ref(''),
    errors: ref(''),
    isHidden: ref(false),
    // filters: Object,
});

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

// FilePond.setOptions = ({
//     fileMetadataObject: {
//         show_id: '1',
//     },
// });

// let s3 = new AWS.S3({
//     accessKeyId: 'access-key',
//     secretAccessKey: 'secret-access-key',
//     region: 'eu-central-1'
// });
//
// FilePond.setOptions({
//     server: {
//         process: function(fieldName, file, metadata, load, error, progress, abort) {
//
//             s3.upload({
//                 Bucket: 'your-bucket-name',
//                 Key: Date.now() + '_' + file.name,
//                 Body: file,
//                 ContentType: file.type,
//                 ACL: 'public-read'
//             }, function(err, data) {
//
//                 if (err) {
//                     error('Something went wrong');
//                     return;
//                 }
//
//                 // pass file unique id back to filepond
//                 load(data.Key);
//
//             });
//
//         }
//     }
// })

function filepondInitialized() {
    console.log("Filepond is ready!");
    console.log('Filepond object:', FilePond);
}

function handleProcessedFile(error, file) {
    if (error) {
        console.log("Filepond processed file");
        console.log(error);
        console.log(file);
        return;
    }

    Inertia.reload({
        only: ['video'],
    });

}


let form = useForm({
    name: '',
    description: '',
    video: '',
});

let submit = () => {
    // form.append('form', json);
    // axios.post("/movies/upload", form.data);
    form.post(route('movies.upload', form));
};


</script>
