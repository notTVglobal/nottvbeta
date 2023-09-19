<template>

    <Head :title="`Upload Movie`"/>

    <header id="topDiv" class="md:pageWidth pageWidthSmall">

        <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

        <div class="flex justify-between p-4 m-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
             role="alert"
             v-if="props.errors.video">
                <span class="font-medium">
                    {{ props.errors.video }}
                </span>
        </div>

    </header>


    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-3">
                <div class="mb-4">
                    <h1 class="text-3xl font-semibold">Movie Upload</h1>
                </div>
                <div>
                    <div class="flex flex-wrap-reverse justify-end gap-2">
                        <Link :href="`/dashboard`">
                            <button
                                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                            >Dashboard
                            </button>
                        </Link>
                    </div>
                </div>
            </div>

            <div>
<!--                <form @submit.prevent="submit" enctype="multipart/form-data">-->
<!--                    <div class="bg-orange-800 text-white p-4 mb-6"><span class="font-semibold">Note:</span> File uploads are limited to 500 MB until we implement a chunk method.-->
<!--                    This will require storing the uploaded file on the server in a temp directory, then we can process it and encrypt it using-->
<!--                    FFMPEG and finally upload it to our destination(s), whether that is Digital Ocean Spaces, an attached volume, remote server,-->
<!--                    or a Peer-to-peer distribution network. We also need to implement file validation and restrict the filetypes that can be uploaded.<br>-->
<!--                    For more info about chunking go to:-->
<!--                        <a href="https://github.com/pionl/laravel-chunk-upload" class="text-blue-400 hover:text-blue-200" target="_blank">-->
<!--                            https://github.com/pionl/laravel-chunk-upload</a>-->
<!--                        <br><br>-->
<!--                        Also look at: <a href="https://pineco.de/chunked-file-upload-with-laravel-and-vue/" class="text-blue-400 hover:text-blue-200" target="_blank">-->
<!--                            https://pineco.de/chunked-file-upload-with-laravel-and-vue/-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <input-->
<!--                        v-model="form.name"-->
<!--                        type="text"-->
<!--                        name="name"-->
<!--                        id="name"-->
<!--                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"-->
<!--                        placeholder="Movie Title"-->
<!--                    />-->
<!--                    <div v-if="form.errors.name" v-text="form.errors.name"-->
<!--                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->
<!--                    <textarea-->
<!--                        v-model="form.description"-->
<!--                        type="text"-->
<!--                        name="description"-->
<!--                        id="description"-->
<!--                        cols="30"-->
<!--                        row="20"-->
<!--                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"-->
<!--                        placeholder="Description"-->
<!--                    />-->
<!--                    <div v-if="form.errors.description" v-text="form.errors.description"-->
<!--                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->
<!--                    <input-->
<!--                        v-model="form.file_url"-->
<!--                        type="text"-->
<!--                        name="file_url"-->
<!--                        id="file_url"-->
<!--                        class="border border-gray-400 rounded w-full px-2 py-2 my-2"-->
<!--                        placeholder="Link to existing video file (optional)"-->
<!--                    />-->
<!--                    <div v-if="form.errors.file_url" v-text="form.errors.file_url"-->
<!--                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->

<!--                    <div-->
<!--                        @dragenter.prevent="toggleActive"-->
<!--                        @dragleave.prevent="toggleActive"-->
<!--                        @dragover.prevent-->
<!--                        @drop.prevent="drop"-->
<!--                        :class="{ 'active-dropzone': active }"-->
<!--                        class="dropzone mt-4">-->
<!--                        <span>Drag or Drop Video</span>-->
<!--                        <span>OR</span>-->
<!--                        <label for="dropzoneFile" class="cursor-pointer hover:bg-gray-600">Select Video</label>-->
<!--                        <input-->
<!--                            type="file"-->
<!--                            name="video"-->
<!--                            id="dropzoneFile"-->
<!--                            class="dropzoneFile border border-gray-400 rounded w-full px-2 py-2 my-2"-->
<!--                            @change="selectedFile"-->
<!--                            ref="fileInput"-->
<!--                            accept="video/*"-->
<!--                            @input="form.video = $event.target.files[0]"-->
<!--                            style="display: none"/>-->
<!--                    </div>-->

<!--                    <progress :value="progress"></progress>-->

<!--                    <div class="mt-2">File: {{ dropzoneFile.name }}</div>-->

<!--                    <div v-if="form.errors.video" v-text="form.errors.video"-->
<!--                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->


<!--                    <button-->
<!--                        @click="submit"-->
<!--                        class="bg-green-600 hover:bg-green-500 text-white rounded py-2 px-4 mt-2"-->
<!--                        :disabled="form.processing"-->
<!--                    >-->
<!--                        Upload-->
<!--                    </button>-->

<!--                </form>-->

                <div> New chunk uploader</div>
                <input
                    type="file"
                    @change="select"
                    name="video"
                    class="dropzoneFile border border-gray-400 rounded w-full px-2 py-2 my-2"
                    ref="fileInputChunk"
                    accept="video/*"
                    @input="form.video = $event.target.files[0]"
                    />
                >
                <progress :value="progress" class="w-full"></progress>

            </div>

        </div>
    </div>

</template>

<script setup>
import Message from "@/Components/Modals/Messages"
import {ref, onMounted, onBeforeMount} from "vue"

import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'movies'
userStore.showFlashMessage = true;

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
});

let props = defineProps({
    errors: ref(''),
    isHidden: ref(false),
    // filters: Object,
});

// let form = useForm({
//     name: '',
//     description: '',
//     video: '',
//     file_url: '',
// });

// Dropzone tutorial: https://www.youtube.com/watch?v=wWKhKPN_Pmw

// let dropzoneFile = ref([]);
// // let chunks = ref([]);
// //
// const active = ref(false);
// //
// const toggleActive = () => {
//     active.value = !active.value;
// }
// //
// //
// //
// const drop = (e) => {
//     dropzoneFile.value = e.dataTransfer.files[0];
//     active.value = !active.value;
// }
// //
// const selectedFile = () => {
//     dropzoneFile.value = document.querySelector('.dropzoneFile').files[0];
// }
//
// const createChunks = (e) => {
//     let size = 2048,
//         chunks = Math.ceil(e.file.size / size);
//
//     for ( let i = 0; i < chunks; i++ ) {
//         e.chunks.push(e.file.slice(
//             i * size, Math.min(i * size + size, e.file.size), e.file.type
//         ));
//     }
// }
//
// function upload() {
//     axios(this.config).then(response => {
//         this.chunks.shift();
//     }).catch(error => {});
// }
//

//
let submit = () => {
    // form.append('form', json);
    // axios.post("/movies/upload", form.data);
    form.post(route('movies.store', form));
};


</script>

<script>
export default {
    watch: {
        chunks(n, o) {
            if (n.length > 0) {
                this.upload();
            }
        }
    },

    data() {
        return {
            file: null,
            chunks: [],
            uploaded: 0
        };
    },

    computed: {
        progress() {
            return Math.floor((this.uploaded * 100) / this.file.size);
        },
        formData() {
            let formData = new FormData;

            formData.set('is_last', this.chunks.length === 1);
            formData.set('file', this.chunks[0], `${this.file.name}.part`);

            return formData;
        },
        config() {
            return {
                method: 'POST',
                data: this.formData,
                url: 'movies/upload',
                headers: {
                    'Content-Type': 'application/octet-stream'
                },
                onUploadProgress: event => {
                    this.uploaded += event.loaded;
                }
            };
        }
    },

    methods: {
        select(event) {
            this.file = event.target.files.item(0);
            this.createChunks();
        },
        upload() {
            axios(this.config).then(response => {
                this.chunks.shift();
            }).catch(error => {});
        },
        createChunks() {
            let size = 2048, chunks = Math.ceil(this.file.size / size);

            for (let i = 0; i < chunks; i++) {
                this.chunks.push(this.file.slice(
                    i * size, Math.min(i * size + size, this.file.size), this.file.type
                ));
            }
        }
    }
}
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
