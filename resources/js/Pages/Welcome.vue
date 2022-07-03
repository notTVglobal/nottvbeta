<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});


</script>

<template>
    <Head title="Beta" />
<div class="g-gray-800 min-h-screen text-gray-200">

    <div class="relative flex items-top justify-center min-h-screen bg-gray-800 text-gray-200 sm:items-center sm:pt-0">
        <div class="flex justify-center items-center h-screen">
            <JetApplicationLogo class="block w-auto"/>
        </div>
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.user" :href="route('dashboard')" class="text-sm text-gray-200 underline">
                Dashboard
            </Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-200 underline">
                    Log in
                </Link>
                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-200 underline">
                    Register

                </Link>

            </template>
        </div>

    </div>
</div>
</template>

<style scoped>

</style>
