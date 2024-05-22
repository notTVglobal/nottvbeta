<template>
    <Head title="Privacy Policy"/>

    <div class="font-sans text-gray-900 antialiased ">
        <div class="pt-4 bg-gray-100 rounded">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <JetAuthenticationCardLogo/>
                </div>

                <div class="privacy mb-5 mt-10 overflow-scroll">
                    <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md sm:rounded-lg prose"
                         v-html="policy"/>
                </div>

            </div>
        </div>
    </div>

</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

defineProps({
    policy: String,
});
</script>
<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
    layout: NoLayout,
}
</script>
<style scoped>

.privacy {
    height: 80vh;
}
</style>
