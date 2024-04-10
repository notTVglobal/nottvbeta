<template>
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 min-h-screen">
<!--      <div class="flex flex-col w-full text-center justify-center mt-10">-->
<!--        <img-->
<!--            src="https://cdn.nottv.io/public/2023/09/images/pTJbtM0IeHCV1qzSFNeJD7H2mogeYM7MSzCCo1m0.png"-->
<!--            alt="celebration emoji"-->
<!--            class="w-20">-->

<!--        <div class="text-4xl pl-10 pt-5">You are now a premium subscriber!</div>-->

<!--      </div>-->

      <div class="flex flex-col items-center justify-center mt-10">
        <!-- Consider using a custom SVG or an image that reflects your brand's theme instead of a generic emoji -->
        <img src="https://cdn.nottv.io/public/2023/09/images/pTJbtM0IeHCV1qzSFNeJD7H2mogeYM7MSzCCo1m0.png" alt="Celebration" class="w-32 h-32 md:w-40 md:h-40"> <!-- Adjust the sizes as needed -->

        <!-- Use your brand colors and potentially a custom font here -->
        <h2 class="text-4xl font-bold text-yourBrandPrimaryColor mt-5">You're now a Premium Contributor!</h2>

        <!-- Customize this message to sound more like your brand's voice -->
        <p class="mt-6 text-lg md:text-xl max-w-2xl mx-auto text-center">
          A huge thank you for supporting independent media and the free press. Your subscription powers our mission to connect communities with creative and inspiring content.
        </p>

        <p class="mt-4">
          We deeply value your support. If you have any questions or feedback, don’t hesitate to reach out at
          <a href="mailto:cathy@not.tv" class="font-medium text-blue-600 hover:text-blue-400 transition duration-300">cathy@not.tv</a>.
        </p>

        <!-- Adding a button or link to guide the user on what to do next could be beneficial -->
        <p class="mt-6">
          Dive into your Premium experience now! If you don't see the premium features, a quick <span class="font-medium text-yourBrandSecondaryColor">refresh</span> of your browser page should do the trick.
        </p>

        <!-- Consider adding a button for users to explore premium content or return to the homepage -->
        <button @click="openChannelsOtt" class="mt-6 inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-400 transition duration-300">
          Explore Premium Content 🌟
        </button>
        <div v-if="exploreChannelsDisplay" class="mt-4 p-4 bg-blue-100 text-blue-800 rounded-lg">
          <h2>Our premium features include:</h2>
          <p class="flex items-center justify-start gap-2 font-semibold">
            <span class="text-2xl">📺</span>
            CHANNELS: Change channels from the green
            <span class="underline">Channels Menu
              <span v-if="userStore.isMobile">^^^</span>
              <span v-if="!userStore.isMobile">&gt;&gt;&gt;</span>
              </span></p>
          <p class="flex items-center justify-start gap-2 font-semibold"><span class="text-2xl">🎬</span> MOVIES: Check out <Link @click.prevent="appSettingStore.btnRedirect('/movies')" class="underline text-blue-800 hover:text-blue-600 transition duration-300">movies</Link> from the main menu.</p>
          <p class="mt-4 flex items-center justify-start gap-2 font-semibold"><span class="text-2xl">🔜</span> PLAYLISTS: Our playlists feature and other features are coming soon... you'll be the first to know when they're ready!</p>
        </div>
        <img src="/storage/images/Ping.png" alt="notTV Ping" class="w-fit mt-4">
      </div>


</div>

      <!--      <div class="text-center w-full mt-6 pt-3">Thank you for supporting independent media and free press.<br>-->
<!--        <span class="pt-2">We value your subscription, if you have any questions or comments,</span><br>-->
<!--        Please email <a href="mailto:cathy@not.tv" class="text-blue-600 hover:text-blue-500">cathy@not.tv</a></div>-->
<!--      <div class="text-center w-full mt-6 pt-3">If you don't see the premium features you may need to refresh your-->
<!--        browser page.-->
<!--      </div>-->
<!--    </div>-->
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { onMounted, ref, getCurrentInstance } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"

usePageSetup('contribute/subscription_success')

const userStore = useUserStore()
const appSettingStore = useAppSettingStore()

let props = defineProps({
  userIsSubscriber: null
})

onMounted(async () => {
  if (props.userIsSubscriber) {
    userStore.isSubscriber = true
  }
  Inertia.reload()
});

// Access the global properties
const {proxy} = getCurrentInstance()

// Start the confetti
proxy.$confetti.start();

// Stop the confetti after 5 seconds
setTimeout(() => {
  proxy.$confetti.stop(); // Replace `stop` with the actual method name to stop the confetti
}, 5000); // 5000 milliseconds = 5 seconds

let exploreChannelsDisplay = ref(false)

const openChannelsOtt = () => {
  appSettingStore.toggleOtt(2)
  exploreChannelsDisplay.value = true
}

</script>
