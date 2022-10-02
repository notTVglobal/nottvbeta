<template>

    <Head :title="title" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <h1 class="text-3xl"><Link :href="`/shows/${props.show.id}`" class="text-indigo-600">{{props.show.name}}</Link> > <span class="font-semibold">Edit</span></h1>
                <span class="text-xs font-semibold text-red-700">Edit Mode</span>
                <div>
                    <Link :href="`/shows/${props.show.id}`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                </div>
            </div>

            <div class="max-w-lg mx-auto mt-8">
                <div class="mb-6">Show ID: {{props.show.id}}</div>
                <div class="flex space-y-3">
                    <div class="mb-6"><img :src="`/storage/images/${props.show.poster}`" /></div>
                    <file-pond
                        name="image"
                        ref="pond"
                        label-idle="Click to change poster"
                        server="/upload"
                        @init="filepondInitialized"
                        accepted-file-types="image/jpg, image/jpeg, image/png"
                        @processfile="handleProcessedFile"
                        allow-multiple="true" max-files="10"
                        max-file-size="20MB"
                    />
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="name"
                        >
                            Show Name
                        </label>

                        <input v-model="form.name"
                               class="border border-gray-400 p-2 w-full rounded-lg"
                               type="text"
                               name="name"
                               id="name"
                               required
                        >
                        <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="description"
                        >
                            Description
                        </label>
                        <TabbableTextarea v-model="form.description"
                               class="border border-gray-400 p-2 w-full rounded-lg"
                               name="description"
                               id="description"
                               rows="10" cols="30"
                               required
                        />
                        <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
                    </div>

                    <div class="flex justify-between mb-6">
                        <button
                            type="submit"
                            class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
                            :disabled="form.processing"
                        >
                            Submit
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import TabbableTextarea from "@/Components/TabbableTextarea"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import ImageUpload from "@/Components/ImageUpload";
import { ref } from 'vue'

import "filepond/dist/filepond.min.css"
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    show: {
        type: Object,
        default: () => ({}),
        id: String,
    },
    images: Object
});

let title = "Edit > " + props.show.name;

let form = useForm({
    id: ref(props.show.id),
    name: props.show.name,
    description: props.show.description,
    poster: props.show.poster,
});

// let setPoster = () => {props.show.poster = props.image.name};

let submit = () => {
    // setPoster()
    form.put(route('shows.update', props.show.id));
};

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
