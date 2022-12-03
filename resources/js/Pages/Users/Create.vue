<template>
    <Head title="Create User"/>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mt-3 mb-6">
                <div class="text-3xl">Create New User</div>
                <div>
                    <Link :href="`/users`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                </div>
            </div>

        <div
            class="p-4 mb-4 text-sm text-orange700 bg-orange-100 rounded-lg dark:bg-orange-200 dark:text-orange-800"
            role="alert"
        >
                <span class="font-medium">
                    New users will need to "Reset Password" then "re-send the verification email".
                </span>
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
                    <option value="1">Standard User</option>
                    <option value="2">Premium Subscriber</option>
                    <option value="3">VIP</option>
                    <option value="4">Creator</option>
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
            <div class="mb-6 hidden">
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
                       hidden
                >
                <div v-if="form.errors.password" v-text="form.errors.password" class="text-xs text-red-600 mt-1"></div>
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

                <input v-model="form.address1"
                       class="border border-gray-400 p-2 mb-2 w-full rounded-lg"
                       type="text"
                       name="address1"
                       id="address1"
                >

                <input v-model="form.address2"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="address2"
                       id="address2"
                >
                <div v-if="form.errors.address1" v-text="form.errors.address1" class="text-xs text-red-600 mt-1"></div>
                <div v-if="form.errors.address2" v-text="form.errors.address2" class="text-xs text-red-600 mt-1"></div>
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

                <input v-model="form.postalCode"
                       class="border border-gray-400 p-2 w-full rounded-lg"
                       type="text"
                       name="postalCode"
                       id="postalCode"
                >
                <div v-if="form.errors.postalCode" v-text="form.errors.postalCode" class="text-xs text-red-600 mt-1"></div>
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
import {onMounted, ref} from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.currentPage = 'users'

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let form = useForm({
    name: '',
    email: '',
    password: ' ',
    role_id: '',
    address1: null,
    address2: null,
    city: null,
    province: null,
    country: null,
    postalCode: null,
    phone: null,
});

// const role = ref(null);

function reset() {
    form.reset();
};

const submit = () => {
    form.post(route("users.store"));
};

</script>
