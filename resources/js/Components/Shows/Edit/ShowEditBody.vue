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
                         :key="poster" />
                </div>
            </div>
        </div>

<!--Right Column-->
        <div>
            <ShowPosterUpload
                :team="props.show"
                :images="props.images"
            />

            <form @submit.prevent="submit">
                <div class="mb-6">
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        Image ID
                    </label>

                    <input v-model="form.image_id"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="image_id"
                           id="image_id"
                           required
                    >
                    <div v-if="form.errors.image_id" v-text="form.errors.image_id"
                         class="text-xs text-red-600 mt-1"></div>
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
import TabbableTextarea from "@/Components/TabbableTextarea"
import ShowPosterUpload from "@/Components/FilePond/ShowPosterUpload";
import { ref } from 'vue'
import {Inertia} from "@inertiajs/inertia";

let showStore = useShowStore()

let props = defineProps({
    show: Object,
    poster: ref(Object),
});


showStore.posterName = props.poster[0].name;

let form = useForm({
    id: props.show.id,
    name: props.show.name,
    description: props.show.description,
    image_id: ref(props.show.image_id),
});


let submit = () => {
    form.image_id = showStore.posterId;
    form.put(route('shows.update', props.show.id));
};

</script>
