<template>
    <Head title="Create User"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

        <div class="flex justify-between mb-6">
            <h1 class="text-3xl">Create New User</h1>
            <Link href="/admin/users" class="text-blue-500 text-sm ml-2">Go back</Link>
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="name"
                >
                    User Role
                </label>
                <select name="role"
                        id="role"
                        class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-700"
                        v-model="form.role_id"
                        required
                >
                    <option value="2">Standard User</option>
                    <option value="3">Premium Subscriber</option>
                    <option value="4">VIP</option>
                    <option value="5">Creator</option>
                </select>

                <div v-if="form.errors.role_id" v-text="form.errors.role_id" class="text-xs text-red-600 mt-1"></div>
            </div>
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
                       for="password"
                >
                    Password
                </label>

                <input v-model="form.password"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="password"
                       name="password"
                       id="password"
                       required
                >
                <div v-if="form.errors.password" v-text="form.errors.password" class="text-xs text-red-600 mt-1"></div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="email"
                >
                    Phone Number
                </label>

                <input v-model="form.user_phone"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="phone"
                       id="phone"
                >
                <div v-if="form.errors.user_phone" v-text="form.errors.user_phone" class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="email"
                >
                    Address
                </label>

                <input v-model="form.user_address_1"
                       class="border border-gray-400 p-2 mb-2 w-full rounded-lg"
                       type="text"
                       name="address_1"
                       id="address_1"
                >

                <input v-model="form.user_address_2"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="address_2"
                       id="address_2"
                >
                <div v-if="form.errors.user_address_1" v-text="form.errors.user_address_1" class="text-xs text-red-600 mt-1"></div>
                <div v-if="form.errors.user_address_2" v-text="form.errors.user_address_2" class="text-xs text-red-600 mt-1"></div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="email"
                >
                    City
                </label>

                <input v-model="form.user_city"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="city"
                       id="city"
                >
                <div v-if="form.errors.user_city" v-text="form.errors.user_city" class="text-xs text-red-600 mt-1"></div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="email"
                >
                    Province
                </label>

                <input v-model="form.user_province"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="province"
                       id="province"
                >
                <div v-if="form.errors.user_province" v-text="form.errors.user_province" class="text-xs text-red-600 mt-1"></div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="email"
                >
                    Country
                </label>

                <input v-model="form.user_country"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="country"
                       id="country"
                >
                <div v-if="form.errors.user_country" v-text="form.errors.user_country" class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="mb-6">
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                       for="email"
                >
                    Postal Code
                </label>

                <input v-model="form.user_postal_code"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="postal_code"
                       id="postal_code"
                >
                <div v-if="form.errors.user_postal_code" v-text="form.errors.user_postal_code" class="text-xs text-red-600 mt-1"></div>
            </div>
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
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import { ref } from "vue"
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
    email: '',
    password: '',
    role_id: '',
    user_address_1: '',
    user_address_2: '',
    user_city: '',
    user_province: '',
    user_country: '',
    user_postal_code: '',
    user_phone: '',
});

// const role = ref(null);

function reset() {
    form.reset();
};

let submit = () => {
    form.post('/admin/users');
};

</script>
