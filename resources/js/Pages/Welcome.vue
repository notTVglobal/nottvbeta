<template>
    <Head title="Beta" />
        <div id="topDiv"
             class="w-full h-screen place-self-center flex flex-col overscroll-none bg-black text-gray-200 z-50"

             :class="welcomeOverlayBG">
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
                    </div>
                </div>
            </header>

            <div class="welcomeOverlay">
                <div class="relative flex items-top justify-center min-h-screen text-gray-200">

                    <div class="w-full flex justify-center items-center h-screen">

                        <WelcomeOverlay v-show="welcomeStore.showOverlay"
                                        @watchNow="watchNow"/>
                        <VideoControlsWelcome v-show="!welcomeStore.showOverlay" />

                    </div>
                </div>
            </div>


            <section class="flex flex-col justify-center items-center min-h-screen bg-white text-black p-5">

                    <div class="text-center font-semibold text-3xl">Welcome to notTV</div>
                    <div class="text-center text-2xl">Where artists own the platform.</div>
                <span class="text-sm italic mt-12">Log in for more features!</span>
<!--                    <div class="mt-32 text-center italic">(Log in to chat)</div>-->

            </section>
            <section class="grid md:grid-cols-2 min-h-screen content-center gap-10 bg-gray-300 text-white p-10">
                <div class="px-6 py-20 bg-fuchsia-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-star" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Rewards</h2>
                    <p class="text-center text-2xl">Reap the rewards of an artist owned cooperative blockchain media distribution platform and streaming service.</p>
                </div>
                <div class="px-6 py-20 bg-green-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-users" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Community</h2>
                    <p class="text-center text-2xl">Discover new content creators and get engaged with your local community through this new Internet Broadcasting project.</p>
                </div>
                <div class="px-6 py-20  bg-blue-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-hands-helping" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Public good</h2>
                    <p class="text-center text-2xl">Help fund the public good, free speech and free press automatically as part of the business model to keep
                an open and accountable democracy.</p>
                </div>
                <div class="px-6 py-20  bg-purple-600 rounded">
                    <div class="font-bold text-4xl text-center pb-3 space-x-2">
                        <font-awesome-icon icon="fa-solid fa-rocket" />
                    </div>
                    <h2 class="font-bold text-4xl text-center pb-3">Join now</h2>
                    <p class="text-center text-2xl">Watch live streams, local content, and chat with other viewers. Creators register content as your very own NFT on a blockchain that you own.</p>
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
import { onMounted, ref, computed, onBeforeUnmount } from 'vue'
import Login from "@/Components/Welcome/Login.vue"
import Register from "@/Components/Welcome/Register.vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import { useUserStore } from "@/Stores/UserStore"
import Button from "@/Jetstream/Button"
import WelcomeOverlay from "@/Components/Welcome/WelcomeOverlay"
import WelcomeBug from "@/Components/Welcome/WelcomeBug.vue"
import VideoControlsWelcome from "@/Components/VideoPlayer/VideoControls/VideoControlsWelcome.vue"

let videoPlayerStore = useVideoPlayerStore()
let welcomeStore = useWelcomeStore()
let userStore = useUserStore()

userStore.currentPage = 'welcome'
welcomeStore.showOverlay = true
videoPlayerStore.fullPage = false
videoPlayerStore.loggedIn = false
videoPlayerStore.class = "welcomeVideoClass"
videoPlayerStore.videoContainerClass = "welcomeVideoContainer"

onMounted(() => {
    videoPlayerStore.makeVideoWelcomePage()
    videoPlayerStore.ottClass = 'ottClose'
    videoPlayerStore.ott = 0

});

onBeforeUnmount( () => {
    userStore.currentPage=''
})

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

function watchNow(){
    welcomeStore.showOverlay = false;
    videoPlayerStore.unmute()
}

const welcomeOverlayBG = computed(() => ({
    'bg-opacity-80': welcomeStore.showOverlay,
    'bg-opacity-0': !welcomeStore.showOverlay
}))


</script>


<style scoped>
.headerContainer {
    z-index:500;
}
</style>
