<template>
    <Head title="Create User"/>

    <div class="bg-white rounded text-black p-5 mb-10 py-20 w-3/4">
        <div class="flex justify-between mb-6">
            <h1 class="text-3xl">Create New User</h1>
            <Link href="/admin/users" class="text-blue-500 text-sm ml-2">Go back</Link>
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
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
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

let form = useForm({
    name: '',
    email: '',
    password: '',
});

function reset() {
    form.reset();
};

let submit = () => {
    form.post('/admin/users');
};

</script>
