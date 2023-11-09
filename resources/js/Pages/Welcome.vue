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
                    <span class="text-sm italic mt-12">Log in to chat!</span>
    <!--                    <div class="mt-32 text-center italic">(Log in to chat)</div>-->

                </section>

                <section class="flex flex-col justify-center items-center vh-100 bg-gray-300 text-white px-5 py-20">

                    <div class="text-center font-semibold text-3xl mb-6">Sign up for our Newsletter!</div>
                    <div class="text-center text-2xl">
                        <button
                            class="btn btn-primary text-white"
                            @click="goToNewsletterSignup">
                            Click Here
                        </button>
                    </div>
                    <span class="text-sm italic mt-12">Weekly updates and get notified of new broadcasts!</span>
                    <!--                    <div class="mt-32 text-center italic">(Log in to chat)</div>-->

                </section>

                <section class="flex flex-col justify-center items-center vh-100 bg-gray-900 text-white px-12 md:px-32 py-20">
                    <div class="text-left font-semibold text-xl uppercase my-4">Problem </div>
                    <p class="leading-loose tracking-wide"><span class="text-4xl text-yellow-500">In </span>our society, pressing issues such as education, healthcare, poverty, and systemic challenges often go unaddressed due to limited resources and a lack of transparency. Independent journalism, which plays a vital role in uncovering truths and holding powerful entities accountable, also struggles to secure adequate funding. Additionally, the current digital media landscape is dominated by unaccountable powerful entities, leaving content creators, including independent journalists, live streamers, and social media influencers, at their mercy. This lack of accountability results in unfair compensation, limited creative control, and a lack of transparency.</p>
                    <div class="text-left mt-16 mb-4 font-semibold text-xl uppercase">Solution </div>
                    <p class="leading-loose tracking-wide"><span class="text-4xl text-yellow-500">Unlike </span>traditional media platforms, notTV offers a unique solution—a cooperative ecosystem where content creators are also platform owners. By joining notTV as investors and members, individuals directly contribute to addressing societal problems, ensuring that critical issues are no longer ignored. Profits are shared fairly among cooperative members, ensuring creators receive just compensation for their work. notTV leverages blockchain technology and smart contracts for transparent and secure transactions. It fosters collaboration and community engagement while allocating a percentage of profits to support crucial societal initiatives through the Public Good Fund and independent investigative journalism. Invest in notTV to be part of a transformative movement where content creators thrive, users have a voice, societal problems are actively tackled, and unaccountable powerful entities are held in check. Together, we create a more equitable, informed, and empowered society and media ecosystem.</p>
                </section>

                <section class="grid md:grid-cols-2 vh-100 content-center gap-10 bg-gray-300 text-white p-10">
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

        </div>
    </transition>

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
import videojs from "video.js";

let videoPlayerStore = useVideoPlayerStore()
let welcomeStore = useWelcomeStore()
let userStore = useUserStore()
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
