<template>
    <Head title="Edit User"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <h1 class="text-3xl"><Link :href="`/users/${props.userEdit.id}`" class="text-indigo-600">{{props.userEdit.name}}</Link></h1>
                <span class="text-xs font-semibold text-red-700">Edit Mode</span>
                <div>
                    <Link :href="`/users/${props.userEdit.id}`"><button
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel</button>
                    </Link>
                </div>
            </div>

        <div class="max-w-md mx-auto mt-8">
            <div class="mb-6"><img :src="props.userEdit.profile_photo_url" class="rounded-full h-20 w-20 object-cover"/></div>
            <div class=""><span class="text-xs uppercase">User ID: </span><span class="font-semibold">{{props.userEdit.id}}</span></div>
            <div class=""><span class="text-xs uppercase">Subscription Status: </span><span class="font-semibold">{{props.userEdit.subscriptionStatus}}</span></div>
            <div class="" v-if="props.userEdit.role_id == 4"><span class="text-xs uppercase">Creator #: </span><span class="font-semibold">{{props.userEdit.creatorNumber}}</span></div>

            <form @submit.prevent="submit" class="mt-6">
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                           for="name"
                    >
                        User Role
                    </label>
                    <select class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-700"
                            v-model="form.role_id"
                    >
                        <option value="1">Standard User</option>
                        <option value="2">Premium Subscriber</option>
                        <option value="3">VIP</option>
                        <option value="4">Creator</option>
                    </select>

                    <div v-if="form.errors.roleId" v-text="form.errors.roleId" class="text-xs text-red-600 mt-1"></div>
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
                           for="phone"
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
                           for="address1"
                    >
                        Address 1
                    </label>

                    <input v-model="form.address1"
                           class="border border-gray-400 p-2 mb-2 w-full rounded-lg"
                           type="text"
                           name="address1"
                           id="address1"
                    >
                </div>

                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700"
                               for="address2"
                        >
                            Address 2
                        </label>

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
                           for="city"
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
                           for="province"
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
                           for="country"
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
                           for="postal_code"
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
import {onMounted} from "vue";

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'users'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    userEdit: Object
});

let form = useForm({
    id: props.userEdit.id,
    name: props.userEdit.name,
    email: props.userEdit.email,
    role_id: props.userEdit.role_id,
    address1: props.userEdit.address1,
    address2: props.userEdit.address2,
    city: props.userEdit.city,
    province: props.userEdit.province,
    country: props.userEdit.country,
    postalCode: props.userEdit.postalCode,
    phone: props.userEdit.phone,
});

function reset() {
    form.reset();
};

let submit = () => {
    form.put(route('users.update', props.userEdit.id));
};
// let submit = () => {
//     form.put('/admin/users');
// };

</script>
