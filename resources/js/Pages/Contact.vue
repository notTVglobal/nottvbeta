<template>
  <Head :title="`Contact Us`"/>
  <div id="topDiv" ></div>
  <div :class="marginTopClass">
    <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    <PublicResponsiveNavigationMenu />

    <div class="w-full h-screen bg-gray-900 flex flex-col gap-y-3 place-self-center text-white">

      <div class="pt-20 text-center text-3xl font-semibold tracking-widest uppercase text-gray-50">Contact Us</div>
      <main class="mx-auto border-b border-gray-800 pb-36">

        <div class="text-green-500 py-12 w-56" v-if="$page.props.flash.success && !$page.props.flash.error">Thank You for Reaching Out!<br />Your message is on its way to us. We appreciate you taking the time to get in touch and will respond as quickly as possible. Look forward to speaking with you soon!</div>
        <div class="text-orange-500 py-12" v-if="$page.props.flash.error">There was an error submitting the form. Please email hello@not.tv</div>

        <form class="w-72" v-if="!$page.props.flash.success && !$page.props.flash.error" @submit.prevent="submit">
          <div class="mb-6">We'd love to hear from you! Send us your questions, feedback, or just say hello.</div>
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
            <textarea id="message"
                      v-model="form.message"
                      rows="4"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
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

      <Footer />
    </div>
  </div>
</template>




<script setup>
import { computed, onMounted, watch } from 'vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import Footer from '@/Components/Global/Layout/Footer'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import { useForm } from '@inertiajs/vue3'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.currentPage = 'Contact'
appSettingStore.setPrevUrl()

// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({behavior: 'smooth'})
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({top: 0, behavior: 'smooth'})
    }
  })
}
scrollToTop() // Optionally scroll to top when the component mounts


onMounted(() => {
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }
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
  form.post(route('public.contact.submit'))
}

const marginTopClass = computed(() => {
  return appSettingStore.loggedIn ? '' : 'mt-16';
});



</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
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
