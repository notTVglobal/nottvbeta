<template>
    <Head title="Create a Creator"/>

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

        <div class="flex justify-between mb-6">
            <h1 class="text-3xl">Create New Creator</h1>
            <Link href="/creators" class="text-blue-500 text-sm ml-2">Go back</Link>
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
            <div class="mb-6">This will be a dropdown to upgrade an existing user. This option will only be available to admins.</div>
            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="name"
                >
                    Name
                </label>

                <select v-model="form.name"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       name="name"
                       id="name"
                       required
                >
                    <option></option>
                </select>
                <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>

            <div class="flex justify-between mt-6 mb-6">
                <button
                    type="submit"
                    class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
                    :disabled="form.processing"
                >
                    Upgrade
                </button>
            </div>
            </div>
        </form>

    </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"

let videoPlayer = useVideoPlayerStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false

let form = useForm({
    name: '',
    email: '',
    password: '',
});

function reset() {
    form.reset();
};

let submit = () => {
    form.post('/creators');
};

</script>
