<template>
    <Head title="Terms of Service"/>

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <JetAuthenticationCardLogo/>
                </div>

                <div class="h-full mb-56 mt-10 pb-56 overflow-scroll">
                    <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose"
                        v-html="terms"/>
                </div>



            </div>
        </div>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

defineProps({
    terms: String,
});
</script>
<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
    layout: NoLayout,
}
</script>
