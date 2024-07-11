<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>
<script>
import NoLayout from '../../Layouts/NoLayout';
export default {
    layout: NoLayout,
}
</script>

<template>
    <Head title="Email Verification" />

    <JetAuthenticationCard>
        <template #logo>
            <Link
                :href="`/`"
                class="underline text-sm text-gray-600 hover:text-gray-900 ml-2"
            >
                <img :src="`/storage/images/logo_black_311.png`" alt="image" class="justify-center max-w-[16rem]">
            </Link>
        </template>

<!--        <div class="mb-4 text text-gray-600 font-bold">-->
<!--            Verification email re-sent!-->
<!--        </div>-->

        <div class="my-4 font-medium text-center text-sm text-green-600">
            A new verification link has been sent to your email!
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-center">
                <Link
                    :href="$route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 ml-2"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
