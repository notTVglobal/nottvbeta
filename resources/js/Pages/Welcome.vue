<template>
  <Head title="Beta"/>
  <transition name="fade" mode="out-in">
    <div id="topDiv"
         class=""
         :class="welcomeContainer">
      <header class="headerContainer w-full">
        <div class="w-full flex flex-row md:px-6 py-4 welcomeOverlay">
          <WelcomeBug/>

          <div class="flex justify-end pt-4 md:pr-6 w-full">
            <Button
                class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
                v-if="!$page.props.user" @click="welcomeStore.showLogin = true">
              Log in
            </Button>
            <!--            <Button-->
            <!--                class="bg-opacity-50 hover:bg-opacity-75 text-sm mr-2 md:mr-0 ml-2 md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"-->
            <!--                v-if="!$page.props.user" @click="welcomeStore.showRegister = true">-->
            <!--              &lt;!&ndash;           <Button class="bg-opacity-0 hover:bg-opacity-0"><Link v-if="!$page.props.user" :href="route('register')" class="text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md">&ndash;&gt;-->

            <!--              Register-->
            <!--            </Button>-->
            <Button v-if="!$page.props.user"
                    class="h-fit py-2 px-4 md:py-4 md:px-6 bg-opacity-50 hover:bg-opacity-75 text-lg mr-2 md:mr-0 ml-2 md:text-2xl text-gray-200 hover:text-blue-600 drop-shadow-md"
                    @click="Inertia.visit('register')">Register
            </Button>
          </div>
        </div>
      </header>


      <div class="overscroll-y-scroll">

        <div class="welcomeOverlay">
          <div class="relative flex items-top justify-center min-h-screen text-gray-200">

            <div class="w-full flex justify-center items-center h-screen w-screen">

              <WelcomeOverlay v-show="welcomeStore.showOverlay"
                              @watchNow="watchNow"/>
              <VideoControlsWelcome v-show="!welcomeStore.showOverlay"/>

            </div>
          </div>
        </div>

        <section class="flex flex-col justify-center items-center vh-100 bg-gray-300 text-primary px-5 py-24">

          <div @click="goToNewsletterSignup"
               class="text-center text-lg md:text-xl font-semibold text-white bg-blue-500 hover:bg-blue-600 hover:cursor-pointer px-6 md:px-4 py-2 tracking-wide rounded-lg drop-shadow-lg">
            <span class="text-2xl md:text-3xl">Unlock Exclusive Access:</span><br/> <span class="">Subscribe to Our Newsletter for Your Chance to Receive an Invitation Code!</span>

          </div>

        </section>

        <section class="flex flex-col justify-center items-center vh-100 bg-gray-900 text-white px-12 md:px-32 py-20">
          <div class="text-2xl text-left font-semibold uppercase my-4">Problem</div>
          <p class="text-xl leading-loose tracking-wide"><span class="text-4xl text-yellow-500">In</span>
            our communities, we face big issues like education, health, poverty, and unfair systems, but they often get
            overlooked because we don't have enough resources or clear information. Independent journalists, who are
            important for revealing the truth and keeping powerful groups honest, struggle to find enough funding. Also,
            big companies control digital media, which isn't fair to people like journalists, live streamers, and social
            media influencers. They often don't get paid fairly, can't control their work, and are left in the dark
            about what's happening. Another big problem is that corporate news media sponsors have too much control and
            influence over the stories that get told. This means that we don't always hear the whole story, especially
            if it goes against what these powerful sponsors want.
          </p>
          <div class="text-2xl text-left mt-16 mb-4 font-semibold uppercase">Solution</div>
          <p class="text-xl leading-loose tracking-wide"><span class="text-4xl text-yellow-500">notTV</span>
            is a unique answer to these problems, different from usual media sites. It's a cooperative community where
            content creators also own the platform. Joining notTV means helping to solve big community issues and
            ensuring important topics get the attention they deserve. Members share profits fairly, so creators are
            properly compensated. notTV uses blockchain and smart contracts for safe, transparent deals. This platform
            is about collaboration, community involvement, and keeping the government accountable. A part of the profits
            goes to the Public Good Fund and supports independent journalism. By being part of notTV, you're helping
            create a world where creators succeed, users have a voice, we tackle major issues head-on, and keep both big
            companies and the government in check. Together, we can build a more fair, informed, and empowered society
            and media landscape.
          </p>
        </section>

        <section class="grid md:grid-cols-2 vh-100 content-center gap-10 bg-gray-300 text-white p-10">
          <div class="px-6 py-20 bg-fuchsia-600 rounded">
            <div class="font-bold text-4xl text-center pb-3 space-x-2">
              <font-awesome-icon icon="fa-solid fa-star"/>
            </div>
            <h2 class="font-bold text-4xl text-center pb-3">Rewards</h2>
            <p class="text-center text-2xl">Enjoy a unique media and streaming experience on a platform owned by
              artists, where blockchain technology ensures fairness and creativity for everyone.</p>
          </div>
          <div class="px-6 py-20 bg-green-600 rounded">
            <div class="font-bold text-4xl text-center pb-3 space-x-2">
              <font-awesome-icon icon="fa-solid fa-users"/>
            </div>
            <h2 class="font-bold text-4xl text-center pb-3">Community</h2>
            <p class="text-center text-2xl">Discover and connect with innovative content creators, and become more
              involved with your local community through this exciting Internet Broadcasting project.</p>
          </div>
          <div class="px-6 py-20  bg-blue-600 rounded">
            <div class="font-bold text-4xl text-center pb-3 space-x-2">
              <font-awesome-icon icon="fa-solid fa-hands-helping"/>
            </div>
            <h2 class="font-bold text-4xl text-center pb-3">Public good</h2>
            <p class="text-center text-2xl">By being part of this platform, you're supporting free speech and a free
              press, contributing to an open and accountable democracy.</p>
          </div>
          <div class="px-6 py-20  bg-purple-600 rounded">
            <div class="font-bold text-4xl text-center pb-3 space-x-2">
              <font-awesome-icon icon="fa-solid fa-rocket"/>
            </div>
            <h2 class="font-bold text-4xl text-center pb-3">Join now</h2>
            <p class="text-center text-2xl">Explore a world of live streams and local content, interact with other
              viewers, and see how creators are revolutionizing media by registering their work as NFTs on a
              blockchain.</p>
          </div>
        </section>

        <section class="flex justify-center items-center vh-100 bg-green-800 px-5 py-20">
          <div class="text-2xl">#mediaforabetterworld</div>
        </section>

        <Footer/>


      </div>

    </div>
  </transition>

  <Teleport to="body">
    <Login :show="welcomeStore.showLogin===true" :userType="userType" @close="welcomeStore.showLogin = false"/>
    <Register :show="welcomeStore.showRegister===true" :userType="userType" @close="welcomeStore.showRegister = false"/>
  </Teleport>


  <dialog id="flashErrorModal" class="modal">

    <div class="modal-box text-center my-auto border-2 border-secondary" data-theme="dark">
      <ApplicationLogo class="w-20"/>
      <h2 class="font-bold text-3xl text-secondary">
        Notice
      </h2>
      <p class="py-4 text-xl">
        {{ errorMessage }}
      </p>
      <div class="modal-action justify-center w-full">
        <form method="dialog">
          <!-- if there is a button in form, it will close the modal -->
          <button class="btn btn-secondary">Okay</button>
        </form>
      </div>
    </div>
  </dialog>

</template>

<script setup>
import { onMounted, ref, watchEffect, computed, onBeforeUnmount, onBeforeMount } from 'vue'
import videojs from 'video.js'

import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useWelcomeStore } from '@/Stores/WelcomeStore'
import { useUserStore } from '@/Stores/UserStore'
import Button from '@/Jetstream/Button'
import WelcomeOverlay from '@/Components/Pages/Welcome/WelcomeOverlay'
import WelcomeBug from '@/Components/Pages/Welcome/WelcomeBug'
import Register from '@/Components/Pages/Welcome/Register'
import Login from '@/Components/Pages/Welcome/Login'
import VideoControlsWelcome from '@/Components/Global/VideoPlayer/VideoControls/Layout/VideoControlsWelcome'
import Footer from '@/Components/Global/Layout/Footer'
import { Inertia } from '@inertiajs/inertia'
import PopUpModal from '@/Components/Global/Modals/PopUpModal.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'

const page = usePage()
const flash = ref(page.props.value.flash || {}) // Default to an empty object if flash is undefined
const errorMessage = ref('')

const appSettingStore = useAppSettingStore()
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
})

const welcomeContainer = computed(() => ({
  'w-full vh-100 place-self-center flex flex-col text-gray-200 z-50 bg-gray-900 bg-opacity-50': welcomeStore.showOverlay,
  'w-full vh-100 place-self-center flex flex-col text-gray-200 z-50 bg-opacity-0': !welcomeStore.showOverlay,
}))

const goToNewsletterSignup = () => {
  // window.open('https://not.tv/subscribe', '_blank')
  Inertia.visit('/newsletterSignup')
}

appSettingStore.currentPage = 'welcome'
welcomeStore.showLogin = false
welcomeStore.showRegister = false
welcomeStore.showOverlay = true
appSettingStore.fullPage = false
videoPlayerStore.loggedIn = false
videoPlayerStore.class = 'welcomeVideoClass'
videoPlayerStore.videoContainerClass = 'welcomeVideoContainer'

let reloadPage = () => {
  if (appSettingStore.pageReload) {
    appSettingStore.pageReload = false
    window.location.reload(true)
  }
}

// watchEffect(() => {
//   if (flash.value && flash.value.error) {
//     document.getElementById('flashError').showModal();
//   }
// });

onBeforeMount(() => {
  reloadPage()
})

onMounted(() => {
  if (flash.value.error) {
    errorMessage.value = flash.value.error
    document.getElementById('flashErrorModal').showModal()
  }
  videoPlayerStore.makeVideoWelcomePage()
  // document.getElementById('popUpModalForFlashError').showModal()

  appSettingStore.ott = 0
  appSettingStore.pageIsHidden = false
  appSettingStore.noVideo = false

  // Only scroll into view if there are no query strings
  const topDiv = document.getElementById('topDiv')
  if (topDiv) {
    topDiv.scrollIntoView()
  }


})

onBeforeUnmount(() => {
  appSettingStore.currentPage = ''
})

function watchNow() {
  welcomeStore.showOverlay = false
  videoPlayerStore.unMute()
  // Check if the video is muted
  // const videoPlayer = videojs('main-player')
  //
  // if (videoPlayer.muted()) {

  // videoPlayerStore.resumeAudioContextIfNeeded()
  // videoPlayerStore.ensureAudioContextAndNodesReady(videoPlayer);
  // videoPlayerStore.fadeInAudioFromMuted()
  // }
}

</script>


<style scoped>
.headerContainer {
  z-index: 500;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
