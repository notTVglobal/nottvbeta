<template>
    <Head title="Create Show"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

        <div class="flex justify-between mt-3 mb-6">
            <div class="text-3xl">Create Show</div>
            <div>
                <Link :href="`/shows`"><button
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

                <input v-model="form.description"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="description"
                       id="description"
                       required
                >
                <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
            </div>

            <input v-model="form.poster"
                   class="border border-gray-400 p-2 w-full rounded-lg"
                   type="hidden"
                   name="poster"
                   id="poster"
            >
            <div class="flex justify-between mb-6">
                <button
                    type="submit"
                    class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
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
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let form = useForm({
    name: '',
    description: '',
    poster: ''
});

function reset() {
    form.reset();
};

let submit = () => {
    form.post('/shows');
};

</script>
