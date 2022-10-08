<template>

    <Head :title="title"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <header>
                <div class="flex justify-between mb-6">
                    <h1 class="text-3xl">
                        <Link :href="`/shows/${props.show.id}`" class="text-indigo-600">{{ props.show.name }}</Link>
                    </h1>
                    <span class="text-xs font-semibold text-red-700">Edit Mode</span>
                    <div>
                        <Link :href="`/shows/${props.show.id}/manage`">
                            <button
                                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                            >Cancel
                            </button>
                        </Link>

                    </div>
                </div>
            </header>

            <div class=""><span class="text-xs uppercase font-semibold">Show ID: </span>{{ props.show.id }}
            </div>
            <div class="mb-6"><span class="text-xs uppercase font-semibold">Team: </span>{{ props.team }}
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

            <div v-if="form.errors.name" v-text="form.errors.name"
                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
            <div v-if="form.errors.description" v-text="form.errors.description"
                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

<!--            <div class="max-w-lg mx-auto mt-8">-->
<div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">
    <div>
                    <div class="flex space-y-3">
                        <div class="mb-6"><img :src="'/storage/images/' + showStore.poster" ref="poster"/></div>
                    </div>

                    <div class="">


                    </div>

    </div>
    <div>

        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
               for="name"
        >
            Change Show Poster
        </label>
        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">
            <h2 class="text-xl font-semibold">Upload Show Poster</h2>

            <ul class="pb-4">
                <li>Max File Size: <span class="text-orange-400">10MB</span></li>
                <li>File Types accepted: <span class="text-orange-400">jpg, jpeg, png</span></li>
            </ul>
            <file-pond
                name="image"
                ref="pond"
                label-idle="Click to choose image, or drag here..."
                @init="filepondInitialized"
                server="/upload"
                accepted-file-types="image/jpg, image/jpeg, image/png"
                @processfile="handleProcessedFile"
                max-file-size="10MB"
            />
        </div>
                    <form @submit.prevent="submit">
                        <div class="mb-6">




                        </div>

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
                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="text-xs text-red-600 mt-1"></div>
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
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="text-xs text-red-600 mt-1"></div>
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
                        </div>
                    </div>
                </div>
            </div>


        </div>
<!--    </div>-->


</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3"
import TabbableTextarea from "@/Components/TabbableTextarea"
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore.js"
import {useShowStore} from "@/Stores/ShowStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {ref, reactive} from 'vue'
import {Inertia} from "@inertiajs/inertia";
import vueFilePond, {setOptions} from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileMetadata from 'filepond-plugin-file-metadata';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let showStore = useShowStore()

const props = defineProps({
    user: Object,
    show: {
        type: Object,
        id: String,
    },
    poster: String,
    images: {
        data: {
            0: {
                name: String,
                id: Number,
            },
        },
    },
    team: String,
});

showStore.poster = props.poster;

let title = "Edit > " + props.show.name;

let form = useForm({
    id: reactive(props.show.id),
    name: props.show.name,
    description: props.show.description,
    image_id: props.images.data[0].id,
});

let submit = () => {
    form.put(route('shows.update', props.show.id));
};

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginFileMetadata
);

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

    // add the file to our images array

    Inertia.reload({
        only: ['images'],
    });

    // tec21: this works, but a 1-ms delay isn't
    // the best way, if the database is delayed
    // longer than 1-ms the image won't load
    // on the page.

    setTimeout(function () {
        showStore.poster = ref(props.images.data[0].name);
        console.log("wait 1 second");
    }, 100);

}


</script>
