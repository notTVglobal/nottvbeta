<script setup>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

</script>

<script>
import NoLayout from '../Layouts/NoLayout';
export default {
    layout: NoLayout,
}
</script>


<template>
    <Head title="Beta" />
        <div class="bg-gray-800 min-h-screen text-gray-200">

            <div class="relative flex items-top justify-center min-h-screen bg-gray-800 text-gray-200">
                <div class="flex justify-center items-center h-screen">
                    <JetApplicationLogo class="block w-auto p-10"/>
                    SCROLL DOWN
                </div>
                <div v-if="canLogin" class="fixed top-0 right-0 px-6 py-4 sm:block sm:items-center sm:pt-2">
                    <Link v-if="$page.props.user" :href="route('stream')" class="text-sm text-gray-200 underline">
                        Stream
                    </Link>

                    <template v-else>
<!--                        <div class="fixed left-0 top-0 w-36 p-5"><JetApplicationLogo class=""/></div>-->
                        <Link :href="route('login')" class="text-sm text-gray-200 underline">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-200 underline">
                            Register

                        </Link>

                    </template>
                </div>
            </div>
            <div class="flex justify-center items-center h-screen bg-white text-black p-10">
                Welcome to notTV! Where artists own the platform!
            </div>
            <div class="flex justify-center items-center h-screen bg-white text-black p-10">
                <p>Join now to register your content as your very own NFT on a blockchain that you own.</p>
                <p>Reap the rewards of a cooperative blockchain media distribution platform and streaming service.</p>
                <p>Share your audience with other creators.. which means they also share their audience with you!</p>
                <p>Help fund the public good, free speech and free press automatically as part of the business model and original concept to keep
                a free democracy and make the world a better place.</p>
                <p>#mediaforabetterworld</p>
            </div>
        </div>
</template>

<style scoped>

</style>
