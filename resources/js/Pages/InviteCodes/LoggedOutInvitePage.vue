<template>
  <Head :title="`Request an Invite`"/>
  <div id="topDiv" ></div>
  <div :class="marginTopClass">
    <PublicNavigationMenu v-if="!appSettingStore.loggedIn" class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu v-if="!appSettingStore.loggedIn"/>

    <div class="w-full h-screen bg-gray-900 flex flex-col gap-y-3 place-self-center text-white">
      <div class="pt-10 text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">Join the not.TV Creator Community</div>

      <main class="w-5/6 md:w-1/2 xl:w-1/4 justify-center mx-auto border-b border-gray-800 pb-36">
        <p class="text-gray-300 text-lg py-4 px-6 text-center">
          Welcome to the doorstep of innovation and creativity! 🚀 notTV is all about empowering creators like you, providing a platform where your unique voice and vision can soar.
        </p>

        <div class="mx-auto text-center my-6">
          <p class="text-yellow-400 font-bold text-xl md:text-2xl">
            🌈 Got your golden ticket already? Fantastic! Make sure to use the invite link shared with you to jump straight into the action. Let's create wonders together!
          </p>
        </div>


        <p class="text-gray-300 text-lg py-2 px-6 text-center">
          Typically, our invite codes are shared creator-to-creator, weaving a tapestry of inspired individuals. If you've got a passion for creation and a story to share, you're in the right place! 🎨🎥
        </p>

        <p class="text-gray-300text-lg py-2 px-6 text-center">
          Didn't snag an invite yet? No worries! Let us know you're interested, and we'll be in touch when new codes become available. Who knows? The next chapter of your creative journey could start with us. ✨
        </p>

        <p class="text-yellow-500 text-lg py-4 px-6 text-center">
          Fill out the form below to request your invite. We can't wait to see what you'll create with notTV!
        </p>

        <div class="text-green-500 py-12 w-72 md:w-96 mx-auto text-center" v-if="$page.props.flash.success && !$page.props.flash.error">
          <strong>Thank You for Your Interest!</strong><br />Your request is zooming through the digital cosmos to us. We're thrilled you want to join the journey and will get back to you as soon as invites are available. 🌟 Keep creating magic in the meantime!
        </div>

        <div class="text-orange-500 py-12 w-72 md:w-96 mx-auto text-center" v-if="$page.props.flash.error">
          Oops! Seems like there was a hiccup in submitting your request. 🤖 If the problem persists, feel free to reach out directly at hello@not.tv
        </div>
        <form class="w-72 md:w-96 mx-auto" v-if="!$page.props.flash.success && !$page.props.flash.error" @submit.prevent="submit">
          <div class="mb-4">
            <label for="name" class="block text-gray-200 text-sm font-bold mb-2">
              Name:
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   v-model="form.name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   required>
            <div v-if="form.errors.name" v-text="form.errors.name"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-4">
            <label for="email" class="block text-gray-200 text-sm font-bold mb-2">
              Email:
            </label>
            <input type="email"
                   name="email"
                   id="email"
                   v-model="form.email"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   required>
            <div v-if="form.errors.email" v-text="form.errors.email"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <input v-model="form.confirm_email" type="email" name="confirm_email" value="" class="user-verify" tabindex="-1" autocomplete="off" aria-hidden="true">
          <input v-model="form.fax" type="number" name="fax" value="" class="user-verify" tabindex="-1" autocomplete="off" >
          <input v-model="form.website" type="text" name="website" value="" class=" user-verify" tabindex="-1" autocomplete="off" aria-hidden="true">
          <div class="mb-4">
            <label for="phone" class="block text-gray-200 text-sm font-bold mb-2">
              Phone (optional):
            </label>
            <input type="tel"
                   name="phone"
                   id="phone"
                   v-model="form.phone"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <div v-if="form.errors.phone" v-text="form.errors.phone"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-4">
            <label for="message" class="block text-gray-200 text-sm font-bold mb-2">
              Message:
            </label>
            <textarea id="message" v-model="form.message" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      required></textarea>
            <div v-if="form.errors.message" v-text="form.errors.message"
                 class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-3 rounded focus:outline-none focus:shadow-outline"
                    :disabled="form.processing">
              Send
            </button>
          </div>
        </form>

      </main>

      <Footer v-if="!appSettingStore.loggedIn"/>
    </div>
  </div>
</template>




<script setup>
import { computed, onMounted, watch } from 'vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import Footer from '@/Components/Global/Layout/Footer'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import { useForm } from '@inertiajs/inertia-vue3'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.noLayout = true
appSettingStore.noVideo = true
appSettingStore.currentPage = 'newsletterSignup'

onMounted(() => {
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
})


// Watch for changes in the loggedIn state of appSettingStore
watch(() => appSettingStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn;
})

const props = defineProps({

})

let form = useForm({
  name: '',
  email: '',
  phone: '',
  message: '',
  confirm_email: '', // honeypot
  fax: '', // honeypot
  website: '' // honeypot
})

let submit = () => {
  form.post(route('public.invite.submit'))
}

const marginTopClass = computed(() => {
  return appSettingStore.loggedIn ? '' : 'mt-16';
});



</script>

<style scoped>
.user-verify {
  position: absolute;
  left: -5000px;
  top: auto;
  width: 1px;
  height: 1px;
  overflow: hidden;
}
</style>
