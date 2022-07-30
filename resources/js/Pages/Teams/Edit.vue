<template>

    <Head :title="Edit" />
    <div class=" m-auto mt-10 mb-10 w-3/4">
        <div class="bg-white rounded text-black p-5 mb-10">
            <div class="flex justify-between mb-6">
                <h1 class="text-3xl">Edit > {{props.team.name}}</h1>
                <Link href="/teams" class="text-blue-500 text-sm ml-2">Go back</Link>
            </div>
            <div class="max-w-lg mx-auto mt-8">
                <div class="mb-6">team ID: {{props.team.id}}</div>
                <div class="mb-6"><img :src="props.team.team_poster_url" /></div>
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="name"
                        >
                            Team Name
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

let props = defineProps({
    team: Object
});

let title = "Edit > " + props.team.name;

let form = useForm({
    id: props.team.id,
    name: props.team.name,
    description: props.team.description,
});

// let submit = () => {
//     form.put('/teams');
// };
</script>


