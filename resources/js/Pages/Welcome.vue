<template>
    <Head title="Beta" />
        <div class="bg-green-800 bg-opacity-10 min-h-screen text-gray-200 z-50">
            <div v-if="canLogin" class="px-6 py-4 sm:block sm:items-center sm:pt-2">
                <Link v-if="$page.props.user" :href="route('stream')" class="text-2xl text-gray-200 underline">
                    Stream
                </Link>

                <template v-else>
                    <!--                        <div class="fixed left-0 top-0 w-36 p-5"><JetApplicationLogo class=""/></div>-->
                    <div class="flex mr-3 mt-6 space-x-6 justify-end">
                        <Link :href="route('login')" class="ml-4 text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="ml-4 text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md">
                            Register
                        </Link>
                    </div>
                </template>
            </div>
            <div class="relative flex items-top justify-center min-h-screen text-gray-200">
                <div class="flex justify-center items-center h-screen">
<!--                    <template #menu></template>-->
                    <div class="grid md:grid-cols-1 grid-cols-1 align-items-center">
                        <JetApplicationLogo class="block md:w-auto p-10"/>
                        <div class="text-center text-bold text-3xl">SCROLL DOWN</div>
                        <div class="mt-2 text-xs text-center">OR</div>
                        <div class="mt-4 text-center">
                            <button
                                class="text-2xl bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded disabled:bg-gray-400"
                                @click="scrollToElement"
                            ><font-awesome-icon icon="fa-solid fa-play" /> Watch Now  </button>
                        </div>
                    </div>
                </div>

            </div>
            <section class="flex flex-col justify-center items-center h-screen bg-white text-black p-5">

                    <div class="text-center font-semibold text-3xl">Welcome to notTV</div>
                    <div class="text-center text-2xl">Where artists own the platform.</div>

            </section>
            <section class="grid md:grid-cols-2 content-center gap-10 bg-gray-300 text-white p-10">
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
            <section class="flex justify-center items-center h-screen">
                <div class="text-2xl">#mediaforabetterworld</div>
            </section>
            <section class="justify-center items-center h-screen bg-green-900" ref="scrollToMe" v-if="!showDiv">
                <div class="text-2xl"><font-awesome-icon icon="fa-solid fa-rocket" /></div>
            </section>
        </div>

    <Teleport to="body">
<!--        <Login :show="showLogin" :userType="userType" @close="showLogin = false" />-->
        <Login :show="showLogin" :userType="userType" @close="showLogin = false" />

    </Teleport>
</template>

<script setup>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';
import Login from "@/Components/Login.vue"
import {onMounted, ref} from 'vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore";
import {useWelcomeStore} from "@/Stores/WelcomeStore";

let welcomeStore = useWelcomeStore()
let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

// This page loads properly calling these stores here
// instead of calling the store action .makeVideoWelcomePage()
// keep these here.
// videoPlayer.fullPage = false
// videoPlayer.loggedIn = false
videoPlayer.class = "videoBgFull"
videoPlayer.videoContainerClass = "videoContainerHomePage"
// videoPlayer.videoContainerClass = "videoContainerHomePage"
// chat.show = false
// chat.class = 'chatHidden'


onMounted(() => {
    videoPlayer.makeVideoWelcomePage();
});
//------------------------//

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    userType: Number,
    showLogin: Boolean,
});

let showLogin = ref(false)
let showDiv = ref(null)
const scrollToMe = ref(null)


function scrollToElement(){
    scrollToMe.value?.scrollIntoView({behavior: "smooth"})
    setTimeout(() => {
    showDiv.value = true}, 10);
}


</script>

<script>
import AppLayout from '../Layouts/AppLayout';
export default {
    layout: AppLayout
    // methods: {
    //     scrollToElement() {
    //         const el = this.$refs.scrollToMe;
    //         if (el) {
    //             el.scrollIntoView({ behavior: "smooth" });
    //         }
    //     },
    // },
}


</script>


<style scoped>

</style>
