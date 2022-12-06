<template>
    <Head title="Beta" />
        <div class="bg-green-800 bg-opacity-10 min-h-screen text-gray-200 z-50">

<header class="headerContainer w-full">
    <div class="welcomeOverlay flex flex-row md:px-6 py-4 w-full">
        <WelcomeBug />
        <div class="flex justify-end pt-4 md:pr-6 w-full">
                <Button class="bg-opacity-50 hover:bg-opacity-75 text-sm md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md" v-if="!$page.props.user" @click="welcomeStore.showLogin = true" >
                    Log in
                </Button>
                <Button class="bg-opacity-50 hover:bg-opacity-75 text-sm -mr-4 md:mr-0 ml-2 md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md" v-if="!$page.props.user" @click="welcomeStore.showRegister = true" >
                    <!--           <Button class="bg-opacity-0 hover:bg-opacity-0"><Link v-if="!$page.props.user" :href="route('register')" class="text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md">-->

                    Register
                </Button>
                <Button class="bg-opacity-0 hover:bg-opacity-0"><Link v-if="$page.props.user" :href="route('stream')" class="text-sm md:text-2xl top-20 text-gray-200 underline">
                    Return to stream
                </Link></Button>

        </div>
    </div>
</header>

            <div class="welcomeOverlay">
                <div class="bg-opacity-5 relative flex items-top justify-center min-h-screen text-gray-200">

                    <div class="flex justify-center items-center h-screen">

                        <WelcomeOverlay :show="true"/>

                    </div>
                </div>
            </div>


            <section class="flex flex-col justify-center items-center min-h-screen bg-white text-black p-5">

                    <div class="text-center font-semibold text-3xl">Welcome to notTV</div>
                    <div class="text-center text-2xl">Where artists own the platform.</div>
                    <div class="mt-32 text-center italic">(Log in to chat)</div>

            </section>
            <section class="grid md:grid-cols-2 min-h-screen content-center gap-10 bg-gray-300 text-white p-10">
                <div class="px-6 py-20 bg-fuchsia-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-star" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Rewards</h2>
                    <p class="text-center text-2xl">Reap the rewards of a cooperative blockchain media distribution platform and streaming service.</p>
                </div>
                <div class="px-6 py-20 bg-green-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-users" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">New audiences</h2>
                    <p class="text-center text-2xl">Share your audience with other creators.. which means they also share their audience with you!</p>
                </div>
                <div class="px-6 py-20  bg-blue-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-hands-helping" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Public good</h2>
                    <p class="text-center text-2xl">Help fund the public good, free speech and free press automatically as part of the business model and original concept to keep
                a free democracy and make the world a better place.</p>
                </div>
                <div class="px-6 py-20  bg-purple-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-rocket" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Join now</h2>
                    <p class="text-center text-2xl">Register your content as your very own NFT on a blockchain that you own.</p>
                </div>
            </section>
            <section class="flex justify-center items-center min-h-screen bg-green-800">
                <div class="text-2xl">#mediaforabetterworld</div>
            </section>
        </div>

    <Teleport to="body">
        <Login :show="welcomeStore.showLogin===true" :userType="userType" @close="welcomeStore.showLogin = false" />
        <Register :show="welcomeStore.showRegister===true" :userType="userType" @close="welcomeStore.showRegister = false" />
    </Teleport>
</template>

<script setup>
import Login from "@/Components/Welcome/Login.vue"
import Register from "@/Components/Welcome/Register.vue"
import { onMounted, ref } from 'vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore";
import { useWelcomeStore } from "@/Stores/WelcomeStore";
import { Inertia } from "@inertiajs/inertia";
import Button from "@/Jetstream/Button";
import WelcomeOverlay from "@/Components/Welcome/WelcomeOverlay";
import WelcomeBug from "@/Components/Welcome/WelcomeBug.vue";

let welcomeStore = useWelcomeStore()
let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

welcomeStore.showOverlay = true;

// This page loads properly calling these stores here
// instead of calling the store action .makeVideoWelcomePage()
// keep these here.
// videoPlayer.fullPage = false
// videoPlayer.loggedIn = false
videoPlayerStore.class = "welcomeVideoClass"
videoPlayerStore.videoContainerClass = "welcomeVideoContainer"
// videoPlayer.videoContainerClass = "videoContainerHomePage"
// chat.show = false
// chat.class = 'chatHidden'


onMounted(() => {
    videoPlayerStore.makeVideoWelcomePage();
});
//------------------------//

let props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    userType: Number,
    showLogin: Boolean,
    showRegister: Boolean,
    user: Object,
});

let showLogin = ref(false)
let showRegister = ref(false)
welcomeStore.showLogin = false
welcomeStore.showRegister = false

function ifLoggedIn() {
    if (props.user != null) {
        Inertia.visit('stream')
    } else
    { return }
}
ifLoggedIn();


</script>

<script>
// import AppLayout from '../Layouts/AppLayout';
// export default {
//     layout: AppLayout
//     // methods: {
//     //     scrollToElement() {
//     //         const el = this.$refs.scrollToMe;
//     //         if (el) {
//     //             el.scrollIntoView({ behavior: "smooth" });
//     //         }
//     //     },
//     // },
// }


</script>


<style scoped>
.headerContainer {
    z-index:500;
}
</style>
