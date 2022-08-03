<template>

    <Head :title="title" />

    <div class="place-self-center flex flex-col gap-y-3 mr-96">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <h1 class="text-3xl">Edit > {{props.show.name}}</h1>
                <Link href="/shows" class="text-blue-500 text-sm ml-2">Go back</Link>
            </div>
            <div class="max-w-lg mx-auto mt-8">
                <div class="mb-6">Show ID: {{props.show.id}}</div>
                <div class="mb-6"><img :src="props.show.show_poster_url" /></div>
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
import { useForm } from "@inertiajs/inertia-vue3";
import TabbableTextarea from "@/Components/TabbableTextarea";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"

let props = defineProps({
    show: Object
});

let title = "Edit > " + props.show.name;

let form = useForm({
    id: props.show.id,
    name: props.show.name,
    description: props.show.description,
});

// let submit = () => {
//     form.put('/shows');
// };
</script>


