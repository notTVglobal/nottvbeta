<template>
    <Head title="Beta" />
    <transition name="fade" mode="out-in">
        <div id="topDiv"
             class=""
             :class="welcomeContainer">
            <header class="headerContainer w-full">
                <div class="w-full flex flex-row md:px-6 py-4 welcomeOverlay">
                    <WelcomeBug />
                    <div class="flex justify-end pt-4 md:pr-6 w-full">
                            <Button class="bg-opacity-50 hover:bg-opacity-75 text-sm md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md" v-if="!$page.props.user" @click="welcomeStore.showLogin = true" >
                                Log in
                            </Button>
                            <Button class="bg-opacity-50 hover:bg-opacity-75 text-sm mr-2 md:mr-0 ml-2 md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md" v-if="!$page.props.user" @click="welcomeStore.showRegister = true" >
                                <!--           <Button class="bg-opacity-0 hover:bg-opacity-0"><Link v-if="!$page.props.user" :href="route('register')" class="text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md">-->

                                Register
                            </Button>
                    </div>
                </div>
            </header>


            <div class="overscroll-y-scroll">

                <div class="welcomeOverlay">
                    <div class="relative flex items-top justify-center min-h-screen text-gray-200">

                        <div class="w-full flex justify-center items-center h-screen">

                            <WelcomeOverlay v-show="welcomeStore.showOverlay"
                                            @watchNow="watchNow" />
                            <VideoControlsWelcome v-show="!welcomeStore.showOverlay" />

                        </div>
                    </div>
                </div>





                <section class="flex flex-col justify-center items-center vh-100 bg-white text-black px-5 py-20">

                    <div class="text-center font-semibold text-3xl">Welcome to notTV</div>
                    <div class="text-center text-2xl">Independent news, arts and culture.</div>
                    <span class="text-4xl font-bold italic mt-12 text-secondary">Coming Soon!</span>
    <!--                    <div class="mt-32 text-center italic">(Log in to chat)</div>-->

                </section>

                <section class="flex flex-col justify-center items-center vh-100 bg-gray-300 text-primary px-5 py-20">

                    <div class="text-center font-bold text-3xl mb-6">Sign up for our Newsletter<br>for a chance to get an invite code!</div>
                    <div class="text-center text-2xl">
                        <button
                            class="btn btn-primary text-white uppercase"
                            @click="goToNewsletterSignup">
                            Click Here
                        </button>
                    </div>
                    <span class="text-black italic mt-6">Weekly updates and get notified of new broadcasts!</span>
                    <!--                    <div class="mt-32 text-center italic">(Log in to chat)</div>-->

                </section>

                <section class="flex flex-col justify-center items-center vh-100 bg-gray-900 text-white px-12 md:px-32 py-20">
                    <div class="text-left font-semibold text-xl uppercase my-4">Problem </div>
                    <p class="leading-loose tracking-wide"><span class="text-4xl text-yellow-500">In</span>
                        our communities, we face big issues like education, health, poverty, and unfair systems, but they often get overlooked because we don't have enough resources or clear information. Independent journalists, who are important for revealing the truth and keeping powerful groups honest, struggle to find enough funding. Also, big companies control digital media, which isn't fair to people like journalists, live streamers, and social media influencers. They often don't get paid fairly, can't control their work, and are left in the dark about what's happening. Another big problem is that corporate news media sponsors have too much control and influence over the stories that get told. This means that we don't always hear the whole story, especially if it goes against what these powerful sponsors want.
                    </p>
                    <div class="text-left mt-16 mb-4 font-semibold text-xl uppercase">Solution </div>
                    <p class="leading-loose tracking-wide"><span class="text-4xl text-yellow-500">notTV</span>
                        is a unique answer to these problems, different from usual media sites. It's a cooperative community where content creators also own the platform. Joining notTV means helping to solve big community issues and ensuring important topics get the attention they deserve. Members share profits fairly, so creators are properly compensated. notTV uses blockchain and smart contracts for safe, transparent deals. This platform is about collaboration, community involvement, and keeping the government accountable. A part of the profits goes to the Public Good Fund and supports independent journalism. By being part of notTV, you're helping create a world where creators succeed, users have a voice, we tackle major issues head-on, and keep both big companies and the government in check. Together, we can build a more fair, informed, and empowered society and media landscape.
                    </p>
                </section>

                <section class="grid md:grid-cols-2 vh-100 content-center gap-10 bg-gray-300 text-white p-10">
                    <div class="px-6 py-20 bg-fuchsia-600 rounded">
                        <div class="font-bold text-4xl text-center pb-3 space-x-2">
                            <font-awesome-icon icon="fa-solid fa-star" />
                        </div>
                        <h2 class="font-bold text-4xl text-center pb-3">Rewards</h2>
                        <p class="text-center text-2xl">Enjoy a unique media and streaming experience on a platform owned by artists, where blockchain technology ensures fairness and creativity for everyone.</p>
                    </div>
                    <div class="px-6 py-20 bg-green-600 rounded">
                        <div class="font-bold text-4xl text-center pb-3 space-x-2">
                            <font-awesome-icon icon="fa-solid fa-users" />
                        </div>
                        <h2 class="font-bold text-4xl text-center pb-3">Community</h2>
                        <p class="text-center text-2xl">Discover and connect with innovative content creators, and become more involved with your local community through this exciting Internet Broadcasting project.</p>
                    </div>
                    <div class="px-6 py-20  bg-blue-600 rounded">
                        <div class="font-bold text-4xl text-center pb-3 space-x-2">
                            <font-awesome-icon icon="fa-solid fa-hands-helping" />
                        </div>
                        <h2 class="font-bold text-4xl text-center pb-3">Public good</h2>
                        <p class="text-center text-2xl">By being part of this platform, you're supporting free speech and a free press, contributing to an open and accountable democracy.</p>
                    </div>
                        <div class="px-6 py-20  bg-purple-600 rounded">
                            <div class="font-bold text-4xl text-center pb-3 space-x-2">
                                <font-awesome-icon icon="fa-solid fa-rocket" />
                            </div>
                            <h2 class="font-bold text-4xl text-center pb-3">Join now</h2>
                            <p class="text-center text-2xl">Explore a world of live streams and local content, interact with other viewers, and see how creators are revolutionizing media by registering their work as NFTs on a blockchain.</p>
                        </div>
                    </section>

                    <section class="flex justify-center items-center vh-100 bg-green-800 px-5 py-20">
                        <div class="text-2xl">#mediaforabetterworld</div>
                    </section>

                <footer class="footer p-10 bg-gray-900 text-neutral-content py-24 pl-12">
                    <nav>
                        <header class="footer-title">Membership</header>
                            <span class="italic">Links coming soon.</span>
<!--                        <a class="link link-hover">Branding</a>-->
<!--                        <a class="link link-hover">Design</a>-->
<!--                        <a class="link link-hover">Marketing</a>-->
<!--                        <a class="link link-hover">Advertisement</a>-->
                    </nav>
                    <nav>
                        <header class="footer-title">Company</header>
                            <span class="italic">Links coming soon.</span>
<!--                        <a class="link link-hover">About us</a>-->
<!--                        <a class="link link-hover">Contact</a>-->
<!--                        <a class="link link-hover">Jobs</a>-->
<!--                        <a class="link link-hover">Press kit</a>-->
                    </nav>
                    <nav>
                        <header class="footer-title">Legal</header>
                        <a href="http://localhost/terms-of-service" target="_blank" class="link link-hover">Terms of use</a>
                        <a href="http://localhost/privacy-policy" target="_blank" class="link link-hover">Privacy policy</a>
<!--                        <a href="" class="link link-hover">Cookie policy</a>-->
                    </nav>
                </footer>
                <footer class="footer px-10 py-6 border-t bg-gray-900 text-neutral-content border-base-300">
                    <aside class="items-center grid-flow-col">
<!--                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" class="fill-current"><path d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"></path></svg>-->
                        <div>
                            <img :src="`/storage/images/logo_white_512.png`" class="w-1/6 justify-start" alt="image">
                                not
                                <span class="text-xs">
                                &#174;
                                </span>
                                TV &#169; 2009-{{new Date().getFullYear()}}
<!--                                                    <p>notTV Ltd. <br/>Established in 2010.</p>-->
                        </div>

                    </aside>
                    <nav class="md:place-self-center md:justify-self-end">
                        <div class="grid grid-flow-col gap-4">
                            <a href="https://twitter.com/nottv" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg></a>
                            <a href="https://www.youtube.com/@notTV-WeAreNotTV" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></a>
                            <a href="https://www.facebook.com/wearenottv" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
                            <a href="https://t.me/+2yVGxCKH5gY3MWEx" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M2,21 L23,12 L2,3 L2,10 L17,12 L2,14 Z"></path></svg></a>
                        </div>
                    </nav>
                </footer>



            </div>

        </div>
    </transition>

    <Teleport to="body">
        <Login :show="welcomeStore.showLogin===true" :userType="userType" @close="welcomeStore.showLogin = false" />
        <Register :show="welcomeStore.showRegister===true" :userType="userType" @close="welcomeStore.showRegister = false" />
    </Teleport>
</template>

<script setup>
import { onMounted, ref, computed, onBeforeUnmount } from 'vue'
import Login from "@/Components/Pages/Welcome/Login"
import Register from "@/Components/Pages/Welcome/Register"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import { useUserStore } from "@/Stores/UserStore"
import Button from "@/Jetstream/Button"
import WelcomeOverlay from "@/Components/Pages/Welcome/WelcomeOverlay"
import WelcomeBug from "@/Components/Pages/Welcome/WelcomeBug"
import VideoControlsWelcome from "@/Components/Global/VideoPlayer/VideoControls/Layout/VideoControlsWelcome"
import videojs from "video.js"

const videoPlayerStore = useVideoPlayerStore()
const welcomeStore = useWelcomeStore()
const userStore = useUserStore()
let showLogin = ref(false)
let showRegister = ref(false)

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

const welcomeContainer = computed(() => ({
    'w-full vh-100 place-self-center flex flex-col text-gray-200 z-50 bg-gray-900 bg-opacity-50': welcomeStore.showOverlay,
    'w-full vh-100 place-self-center flex flex-col text-gray-200 z-50 bg-opacity-0': !welcomeStore.showOverlay
}))

const goToNewsletterSignup = () => {
    window.open('https://not.tv/subscribe', '_blank');
};

userStore.currentPage = 'welcome'
welcomeStore.showLogin = false
welcomeStore.showRegister = false
welcomeStore.showOverlay = true
appSettingStore.fullPage = false
videoPlayerStore.loggedIn = false
videoPlayerStore.class = "welcomeVideoClass"
videoPlayerStore.videoContainerClass = "welcomeVideoContainer"

onMounted(() => {
    videoPlayerStore.makeVideoWelcomePage()

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false

});

onBeforeUnmount( () => {
    userStore.currentPage=''
})

function watchNow(){
    welcomeStore.showOverlay = false;
// Call the function to unmute and fade the volume over 3 seconds
    unmuteAndFadeVolume(3);
}

// Function to unmute and fade the volume
const unmuteAndFadeVolume = (durationInSeconds) => {
    // Get the Video.js player instance
    const videoPlayer = videojs('main-player');

    // Check if the video is muted
    if (videoPlayer.muted()) {
        // Unmute the video
        videoPlayer.muted(false);
        videoPlayerStore.muted = false

        // Get the current volume (0 to 1)
        const currentVolume = videoPlayer.volume();

        // Set the volume to 0
        videoPlayer.volume(0);

        // Calculate the step size for fading
        const stepSize = currentVolume / (durationInSeconds * 1000 / 100); // Adjust as needed

        // Create a timer to increment the volume gradually
        const fadeTimer = setInterval(() => {
            // Increment the volume
            const newVolume = videoPlayer.volume() + stepSize;

            // Update the video volume
            videoPlayer.volume(newVolume);

            // Check if the volume has reached 1 (100%)
            if (newVolume >= 1) {
                // Clear the timer
                clearInterval(fadeTimer);
            }
        }, 100); // Adjust the interval as needed
    }
};


</script>


<style scoped>
.headerContainer {
    z-index:500;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
