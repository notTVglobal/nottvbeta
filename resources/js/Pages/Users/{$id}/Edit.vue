<template>
    <Head title="Edit User"/>

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex justify-between mb-6">

                <div>
                    <div class="font-bold mb-4 text-red-700">EDIT USER</div>
                    <h1 class="text-3xl"><Link :href="`/users/${props.userEdit.id}`" class="text-red-700 font-bold uppercase">{{props.userEdit.name}}</Link></h1>
                </div>
                <div>
                    <button
                        @click="back"
                        class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
                    >Cancel
                    </button>
                </div>

            </div>

        <div class="max-w-md mx-auto mt-8">
            <div class="flex flex-row justify-between">
                <div>
                    <div class="mb-6"><img :src="props.userEdit.profile_photo_url" class="rounded-full h-20 w-20 object-cover"/></div>
                    <div class=""><span class="text-xs uppercase">User ID: </span><span class="font-semibold">{{props.userEdit.id}}</span></div>
                    <div class=""><span class="text-xs uppercase">Subscription Status: </span><span class="font-semibold">{{props.userEdit.subscriptionStatus}}</span></div>
                    <div class="" v-if="props.userEdit.role_id == 4"><span class="text-xs uppercase">Creator #: </span><span class="font-semibold">{{props.userEdit.creatorNumber}}</span></div>
                </div>
                <div class="flex align-bottom">
                    <button v-if="!isNewsPerson" @click="addUserToNewsroom" class="text-white bg-yellow-600 hover:bg-yellow-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">Add User to Newsroom</button>
                    <button v-if="isNewsPerson" @click="removeUserFromNewsroom" class="text-white bg-yellow-600 hover:bg-yellow-800 hover:text-gray-100 rounded px-4 py-2 w-fit h-12">Remove User from Newsroom</button>
                </div>
            </div>

            <form @submit.prevent="submit" class="mt-6">
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
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
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="name"
                    >
                        Name
                    </label>

                    <input v-model="form.name"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="name"
                           id="name"
                           required
                    >
                    <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="email"
                    >
                        Email
                    </label>

                    <input v-model="form.email"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="email"
                           name="email"
                           id="email"
                           required
                    >
                    <div v-if="form.errors.email" v-text="form.errors.email" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="phone"
                    >
                        Phone Number
                    </label>

                    <input v-model="form.phone"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="phone"
                           id="phone"
                    >
                    <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="address1"
                    >
                        Address 1
                    </label>

                    <input v-model="form.address1"
                           class="border border-gray-400 p-2 mb-2 w-full rounded-lg text-black"
                           type="text"
                           name="address1"
                           id="address1"
                    >
                </div>

                    <div class="mb-6">
                        <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                               for="address2"
                        >
                            Address 2
                        </label>

                    <input v-model="form.address2"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="address2"
                           id="address2"
                    >
                    <div v-if="form.errors.address1" v-text="form.errors.address1" class="text-xs text-red-600 mt-1"></div>
                    <div v-if="form.errors.address2" v-text="form.errors.address2" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="city"
                    >
                        City
                    </label>

                    <input v-model="form.city"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="city"
                           id="city"
                    >
                    <div v-if="form.errors.city" v-text="form.errors.city" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="province"
                    >
                        Province
                    </label>

                    <input v-model="form.province"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="province"
                           id="province"
                    >
                    <div v-if="form.errors.province" v-text="form.errors.province" class="text-xs text-red-600 mt-1"></div>
                </div>
                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="country"
                    >
                        Country
                    </label>

                    <input v-model="form.country"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="country"
                           id="country"
                    >
                    <div v-if="form.errors.country" v-text="form.errors.country" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 uppercase font-bold text-xs light:text-gray-700 dark:text-gray-200"
                           for="postal_code"
                    >
                        Postal Code
                    </label>

                    <input v-model="form.postalCode"
                           class="border border-gray-400 p-2 w-full rounded-lg text-black"
                           type="text"
                           name="postalCode"
                           id="postalCode"
                    >
                    <div v-if="form.errors.postalCode" v-text="form.errors.postalCode" class="text-xs text-red-600 mt-1"></div>
                </div>

                <div class="flex justify-between mb-6">
                    <JetValidationErrors class="mr-4" />
                    <button
                        type="submit"
                        class="h-fit bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500"
                        :disabled="form.processing"
                    >
                        Submit
                    </button>
                    <div v-if="form.errors" v-text="form.errors" class="text-xs text-red-600 mt-1"></div>
                </div>
            </form>
        </div>

    </div>
    </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3"
import { Inertia } from "@inertiajs/inertia";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'users'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
});

let props = defineProps({
    userEdit: Object,
    isNewsPerson: Boolean,
    message: String,
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

function addUserToNewsroom() {
    Inertia.visit('/newsroom/newsperson', {
        method: 'post',
        data: {
            id: props.userEdit.id,
            name: props.userEdit.name
        },
    })
}

function removeUserFromNewsroom() {

    if (confirm("Are you sure you want to remove this person from the news team?")) {
        form.post(route('newsperson.destroy', props.userEdit.id));
        // Inertia.route('/newsroom/newsperson/destroy', {
        //     method: 'post',
        //     data: {
        //         id: props.userEdit.id,
        //     },
        // })
    }
    // Inertia.visit('/newsroom/newsperson/destroy', {
    //     method: 'post',
    //     data: {
    //         id: props.userEdit.id,
    //     },
    // })
}
// let submit = () => {
//     form.put('/admin/users');
// };

let showMessage = ref(true);

function back() {
    window.history.back()
}

</script>
