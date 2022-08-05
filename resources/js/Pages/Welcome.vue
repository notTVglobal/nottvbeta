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
                    <div class="grid md:grid-cols-1 grid-cols-1">
                        <JetApplicationLogo class="block md:w-auto p-10"/>
                        <div class="text-center">SCROLL DOWN</div>
                    </div>
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
            <section class="flex justify-center items-center h-screen bg-white text-black p-10">
                <div class="grid grid-cols-1 gap-2">
                    <div class="text-center font-semibold">Welcome to notTV</div>
                    <div class="text-center">Where artists own the platform.</div>
                </div>
            </section>
            <section class="grid md:grid-cols-2 content-center gap-10 bg-gray-300 text-white p-10">
                <div class="p-20 bg-fuchsia-600 rounded">
                    <h2 class="font-bold text-2xl text-center pb-3">Rewards</h2>
                    <p class="text-center">Reap the rewards of a cooperative blockchain media distribution platform and streaming service.</p>
                </div>
                <div class="p-20 bg-green-600 rounded">
                    <h2 class="font-bold text-2xl text-center pb-3">New audiences</h2>
                    <p class="text-center">Share your audience with other creators.. which means they also share their audience with you!</p>
                </div>
                <div class="p-20 bg-blue-600 rounded">
                    <h2 class="font-bold text-2xl text-center pb-3">Public good</h2>
                    <p class="text-center">Help fund the public good, free speech and free press automatically as part of the business model and original concept to keep
                a free democracy and make the world a better place.</p>
                </div>
                <div class="p-20 bg-purple-600 rounded">
                    <h2 class="font-bold text-2xl text-center pb-3">Join now</h2>
                    <p class="text-center">Register your content as your very own NFT on a blockchain that you own.</p>
                </div>
            </section>
            <section class="flex justify-center items-center h-screen">
                <div class="">#mediaforabetterworld</div>
            </section>
        </div>
</template>

<style scoped>

</style>
