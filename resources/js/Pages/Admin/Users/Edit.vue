<template>
    <Head title="Edit User"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

        <div class="flex justify-between mb-6">
            <h1 class="text-3xl">Edit User: {{ props.user.name }}</h1>
            <Link href="/admin/users" class="text-blue-500 text-sm ml-2">Go back</Link>
        </div>

        <div class="max-w-md mx-auto mt-8">
            <div class="mb-6"><img :src="props.user.profile_photo_url" /></div>
            <div class=""><span class="text-xs uppercase">Subscription Status: </span><span class="font-semibold">{{props.user.subscription_status}}</span></div>
            <div class="" v-if="props.user.role_id == 4"><span class="text-xs uppercase">Creator #: </span><span class="font-semibold">{{props.user.creator_number}}</span></div>

            <form @submit.prevent="submit" class="mt-6">
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        User Role
                    </label>
                    <select class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-700"
                            v-model="form.role" :options="options"
                    >
                        <option value="2">Standard User</option>
                        <option value="3">Premium Subscriber</option>
                        <option value="4">VIP</option>
                        <option value="5">Creator</option>
                    </select>

                    <div v-if="form.errors.role" v-text="form.errors.role" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="my-6">
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
                           for="email"
                    >
                        Phone Number
                    </label>

                    <input v-model="form.phone"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="phone"
                           id="phone"
                    >
                    <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="email"
                    >
                        Address
                    </label>

                    <input v-model="form.address_1"
                           class="border border-gray-400 p-2 mb-2 w-full rounded-lg"
                           type="text"
                           name="address_1"
                           id="address_1"
                    >

                    <input v-model="form.address_2"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="address_2"
                           id="address_2"
                    >
                    <div v-if="form.errors.address_1" v-text="form.errors.address_1" class="text-xs text-red-600 mt-1"></div>
                    <div v-if="form.errors.address_2" v-text="form.errors.address_2" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="email"
                    >
                        City
                    </label>

                    <input v-model="form.city"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="city"
                           id="city"
                    >
                    <div v-if="form.errors.city" v-text="form.errors.city" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="email"
                    >
                        Province
                    </label>

                    <input v-model="form.province"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="province"
                           id="province"
                    >
                    <div v-if="form.errors.province" v-text="form.errors.province" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="email"
                    >
                        Country
                    </label>

                    <input v-model="form.country"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="country"
                           id="country"
                    >
                    <div v-if="form.errors.country" v-text="form.errors.country" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="email"
                    >
                        Postal Code
                    </label>

                    <input v-model="form.postal_code"
                           class="border border-gray-400 p-2 w-full rounded-lg"
                           type="text"
                           name="postal_code"
                           id="postal_code"
                    >
                    <div v-if="form.errors.postal_code" v-text="form.errors.postal_code" class="text-xs text-red-600 mt-1"></div>
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
            <div class=""><span class="text-xs uppercase">User ID: </span><span class="font-semibold">{{props.user.id}}</span></div>
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



function reset() {
    form.reset();
};

// let submit = () => {
//     form.put('/admin/users');
// };

</script>
