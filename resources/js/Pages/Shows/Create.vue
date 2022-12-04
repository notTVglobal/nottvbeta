<template>
    <Head title="Create Show"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mt-3 mb-6">
                <div class="text-3xl">Create Show</div>
                <div>
                    <Link v-if="teamStore.slug" :href="`/teams/${teamStore.slug}/manage`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                    <Link v-if="!teamStore.slug" :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="name"
                        >
                            Team Name
                        </label>
                        <select class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-700"
                                v-model="form.team_id"
                                required

                        >
                            <option
                                v-for="team in props.teams.data"
                                :key="team.id"
                                :value="team.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"

                            >
                                {{ team.name }}
                            </option>

                        </select>


                        <div v-if="form.errors.team_id" v-text="form.errors.team_id" class="text-xs text-red-600 mt-1"></div>
                    </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        Show Name
                    </label>

                    <input v-model="form.name"
                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
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
                    <textarea v-model="form.description"
                              class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                              type="text"
                              name="description"
                              id="description"
                              required
                    ></textarea>
                    <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
                </div>

                <input v-model="form.user_id" hidden>
                <div class="flex justify-between mb-6">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                        :disabled="form.processing"
                    >
                        Submit
                    </button>
                    <div @click="reset" class="text-blue-600 text-sm cursor-pointer">Reset</div>
                </div>

            </form>

    </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()

videoPlayerStore.currentPage = 'shows'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    teams: Object,
    userId: Number,
})

let form = useForm({
    name: '',
    description: '',
    user_id: props.userId,
    team_id: '',
});

form.team_id = teamStore.id;

function reset() {
    form.reset();
};

let submit = () => {
    form.post('/shows');
};

</script>
