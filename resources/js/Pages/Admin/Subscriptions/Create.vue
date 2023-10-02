<template>
    <Head title="Create a Subscription Plan for Stripe" />
    <div id="topDiv">
        <h2 class="text-xl font-semibold leading-tight p-6">
            Create a Subscription Plan for Stripe
        </h2>
        <div>
            <div class="p-6 border-b border-gray-200 text-gray-300">
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <label
                            for="Name"
                            class="block mb-2 text-sm font-medium"
                        >Name</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            name="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>
                    <div class="mb-6">
                        <label
                            for="description"
                            class="block mb-2 text-sm font-medium"
                        >Description</label
                        >
                        <input
                            type="text"
                            v-model="form.description"
                            name="url"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.description"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>
                    <div class="mb-6">
                        <label
                            for="price_id"
                            class="block mb-2 text-sm font-medium"
                        >Price ID</label
                        >
                        <input
                            type="text"
                            v-model="form.price_id"
                            name="price_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.price_id"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.price_id }}
                        </div>
                    </div>
                    <div class="mb-6">
                        <label
                            for="product_id"
                            class="block mb-2 text-sm font-medium"
                        >Product ID</label
                        >
                        <input
                            type="text"
                            v-model="form.product_id"
                            name="product_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder=""
                        />
                        <div
                            v-if="form.errors.product_id"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.product_id }}
                        </div>
                    </div>

                    <div class=" flex justify-start">
                        <button
                            type="submit"
                            class="h-fit text-white bg-blue-700 hover:bg-blue-300 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 "
                            :disabled="form.processing"
                            :class="{ 'opacity-25': form.processing }"
                        >
                            Submit
                        </button>
                        <Link :href="`/feeds`"><button
                            class="h-fit ml-2 px-4 py-2 text-white bg-blue-700 hover:bg-blue-300 rounded-lg"
                        >Cancel</button>
                        </Link>
                        <JetValidationErrors class="ml-4" />
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { onMounted } from "vue"
import {useUserStore} from "@/Stores/UserStore";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import Button from "@/Jetstream/Button.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'Admin/Subscriptions/Create'

defineProps({

})

let form = useForm({
    name: '',
    description: '',
    price_id: '',
    product_id: '',
    image_id: '',
});

let submit = () => {
    form.post(route("subscription-plans.store"));
};

function back() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

})


</script>
