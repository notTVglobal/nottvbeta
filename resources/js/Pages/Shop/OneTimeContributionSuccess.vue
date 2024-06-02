<template>
  <Head :title="`Thank you for your contribution!`"/>
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
        <h2 class="text-4xl font-bold text-yourBrandPrimaryColor mt-5">Thank you for your contribution!</h2>

        <!-- Customize this message to sound more like your brand's voice -->
        <p class="mt-6 text-lg md:text-xl max-w-2xl mx-auto text-center">
          A huge thank you for supporting independent media and the free press. Your donation powers our mission to connect communities with creative and inspiring content.
        </p>

        <p class="mt-4">
          We deeply value your support. If you have any questions or feedback, don’t hesitate to reach out at
          <a href="mailto:cathy@not.tv" class="font-medium text-blue-600 hover:text-blue-400 transition duration-300">cathy@not.tv</a>.
        </p>

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
import { onMounted, getCurrentInstance, ref } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"

usePageSetup('contribute/subscription_success')

const userStore = useUserStore()
const appSettingStore = useAppSettingStore()

let props = defineProps({

})

onMounted(async () => {

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
