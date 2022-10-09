<template>

    <div v-if="form.errors.name" v-text="form.errors.name"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
    <div v-if="form.errors.description" v-text="form.errors.description"
         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

<!-- Begin grid 2-col -->
    <div class="grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6">

<!--Left Column-->
        <div>
            <div class="flex space-y-3">
                <div class="mb-6">
                    <img :src="'/storage/images/' + showStore.posterName"
                         ref="poster" />
                </div>
            </div>
        </div>

<!--Right Column-->
        <div>
            <ShowPosterUpload
                :show="props.show"
                :images="props.images"
            />
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                   for="name"
            >
                Poster Id
            </label>
            <input v-model="form.image_id"
                   class="border border-gray-400 p-2 w-full rounded-lg"
                   :value="showStore.posterId"
            >
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
                        Save
                    </button>
                </div>
            </form>

        </div>
<!-- End Right Column -->
    </div>
<!-- End grid 2-col -->

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore";
import {useForm} from "@inertiajs/inertia-vue3";
import {reactive, ref} from "vue";
import TabbableTextarea from "@/Components/TabbableTextarea"
import ShowPosterUpload from "@/Components/FilePond/ShowPosterUpload";

let showStore = useShowStore()

let props = defineProps({
    show: Object,
    poster: String,
    images: {
        data: {
            0: {
                name: String,
                id: Number,
            },
        },
    },
});

showStore.posterName = props.poster;

let form = useForm({
    id: props.show.id,
    name: props.show.name,
    description: props.show.description,
    image_id: ref(props.poster.id),
});

// function updatePosterId () {
//     props.newPosterId = showStore.posterId
// }

let submit = () => {
    form.image_id = showStore.posterId;
    form.put(route('shows.update', props.show.id));
};

</script>
