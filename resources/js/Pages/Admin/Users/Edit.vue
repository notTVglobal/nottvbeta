<template>
    <Head title="Edit User"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

        <div class="flex justify-between mb-6">
            <h1 class="text-3xl">Edit User</h1>
            <Link href="/admin/users" class="text-blue-500 text-sm ml-2">Go back</Link>
        </div>

        <div class="max-w-md mx-auto mt-8">
            <div class="mb-6"><span class="text-xs uppercase">User ID: </span><span class="font-semibold">{{props.user.id}}</span></div>
            <div class="mb-6"><img :src="props.user.profile_photo_url" /></div>
            <form @submit.prevent="submit">
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        Name
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
                           for="email"
                    >
                        Email
                    </label>

                    <input v-model="form.email"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="email"
                           name="email"
                           id="email"
                           required
                    >
                    <div v-if="form.errors.email" v-text="form.errors.email" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        User Role
                    </label>
                    <select class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-700"
                           v-model="form.role" :options="options"
                    >
                        <option value="1">Standard User</option>
                        <option value="2">Premium Subscriber</option>
                        <option value="3">Creator</option>
                        <option value="4">Administrator</option>
                    </select>

                    <div v-if="form.errors.email" v-text="form.errors.email" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="email"
                    >
                        Email
                    </label>

                    <input v-model="form.email"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="email"
                           name="email"
                           id="email"
                           required
                    >
                    <div v-if="form.errors.email" v-text="form.errors.email" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <span>Create a password reset function (insert button here).</span>
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

let props = defineProps({
    user: Object
});

let form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    role: props.user.role_id,
    address_1: props.user.address_1,
    address_2: props.user.address_2,
    city: props.user.address_city,
    province: props.user.address_province,
    country: props.user.address_country,
    postal_code: props.user.address_postal_code,
    phone: props.user.phone,
    creator_number: props.user._creator_number,
    subscription_status: props.user.subscription_status,

});

let options = [
    {value: null, text: 'P',},
    {value: '1', text: '1'},
    {value: '2', text: '2'}

]

function reset() {
    form.reset();
};

// let submit = () => {
//     form.put('/admin/users');
// };

</script>
