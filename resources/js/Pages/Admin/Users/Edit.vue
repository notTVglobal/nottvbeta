<template>
    <Head title="Edit User"/>

    <div class="bg-white rounded text-black p-5 mb-10 py-20 w-3/4">
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl">Edit User</h1>
            <Link href="/admin/users" class="text-blue-500 text-sm ml-2">Go back</Link>
        </div>

        <div class="max-w-md mx-auto mt-8">
            <div class="mb-6">User ID: {{props.user.id}}</div>
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
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

let props = defineProps({
    user: Object
});

let form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
});

function reset() {
    form.reset();
};

// let submit = () => {
//     form.put('/admin/users');
// };

</script>
