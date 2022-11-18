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
                    <div class="bg-orange-800 text-white p-4">Note: File uploads are limited to 2GB until we implement a chunk method.
                    This will require storing the uploaded file on the server in a temp directory, then we can process it and encrypt it using
                    FFMPEG and finally upload it to our destination(s), whether that is Digital Ocean Spaces, an attached volume, remote server,
                    or a Peer-to-peer distribution network. We also need to implement file validation and restrict the filetypes that can be uploaded.<br>
                    For more info about chunking go to:
                        <a href="https://github.com/pionl/laravel-chunk-upload" class="text-blue-400 hover:text-blue-200" target="_blank">
                            https://github.com/pionl/laravel-chunk-upload</a>
                    </div>
                    <input
                        v-model="form.name"
                        type="text"
                        name="name"
                        id="name"
                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"
                        placeholder="Movie Title"
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
                    <div
                        @dragenter.prevent="toggleActive"
                        @dragleave.prevent="toggleActive"
                        @dragover.prevent
                        @drop.prevent="drop"
                        :class="{ 'active-dropzone': active }"
                        class="dropzone">
                        <span>Drag or Drop File</span>
                        <span>OR</span>
                        <label for="dropzoneFile">Select File</label>
                        <input
                            type="file"
                            name="video"
                            id="dropzoneFile"
                            class="dropzoneFile border border-gray-400 rounded w-full px-2 py-2 my-2"
                            @change="selectedFile"
                            ref="fileInput"
                            accept="video/*"
                            @input="form.video = $event.target.files[0]"
                            style="display: none"/>
                    </div>

                    <div class="mt-2">File: {{ dropzoneFile.name }}</div>

                    <div v-if="form.errors.video" v-text="form.errors.video"
                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

                    <button
                        @click="submit"
                        class="bg-green-600 hover:bg-green-500 text-white rounded py-2 px-4 mt-2"
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



// Dropzone tutorial: https://www.youtube.com/watch?v=wWKhKPN_Pmw

let dropzoneFile = ref([]);
const active = ref(false);
const toggleActive = () => {
    active.value = !active.value;
}
const drop = (e) => {
    dropzoneFile.value = e.dataTransfer.files[0];
    active.value = !active.value;
}
const selectedFile = () => {
    dropzoneFile.value = document.querySelector('.dropzoneFile').files[0];
}

let props = defineProps({
    message: ref(''),
    errors: ref(''),
    isHidden: ref(false),
    // filters: Object,
});



let form = useForm({
    name: '',
    description: '',
    video: '',
});

let submit = () => {
    // form.append('form', json);
    // axios.post("/movies/upload", form.data);
    form.post(route('movies.store', form));
};


</script>

<style scoped>
.dropzone {
    width: 400px;
    height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    row-gap: 16px;
    border: 2px dashed #6b7280;
    transition: 0.3s ease all;
}

label {
    padding: 8px 12px;
    color: #fff;
    background-color: #4bb1b1;
    transition: 0.3s ease all;
}

.active-dropzone {
    color: #fff;
    border-color: #fff;
    background-color: #4bb1b1;
}

.active-dropzone  label {
        background-color: #fff;
        color: #4bb1b1;
    }


</style>
