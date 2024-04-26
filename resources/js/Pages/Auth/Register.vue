<template>

  <Head title="Register"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">


      <div
          class="flex flex-col mt-36 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div>
          <ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/>
        </div>
        <div class="mt-8 uppercase">Start Your Experience</div>
      </div>
      <main class="pb-8 hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <div
              class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">

            <JetValidationErrors class="mb-4"/>

            <div class="my-4 pb-4 text-center">
              Create Your Account to Start Exploring notTV!
            </div>

            <form @submit.prevent="submit" class="pb-10">

              <div>

                <label for="name" class="input input-bordered input-primary flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                       class="w-4 h-4 opacity-70">
                    <path
                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z"/>
                  </svg>
                  <input id="name"
                         v-model="form.name" type="text" class="grow border-0" placeholder="Name" autocomplete="name"
                         required/>
                </label>
              </div>
              <div v-if="form.errors.name" v-text="form.errors.name"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <label for="email" value="Email" class="input input-bordered input-primary flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                       class="w-4 h-4 opacity-70">
                    <path
                        d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"/>
                    <path
                        d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"/>
                  </svg>
                  <input id="email"
                         v-model="form.email" type="email" class="grow border-none" placeholder="Email" required/>
                </label>
              </div>
              <div v-if="form.errors.name" v-text="form.errors.name"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <label for="password" class="input input-bordered input-primary flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                       class="w-4 h-4 opacity-70">
                    <path fill-rule="evenodd"
                          d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                          clip-rule="evenodd"/>
                  </svg>
                  <input id="password"
                         v-model="form.password" type="password" class="grow border-none" value="" required/>
                </label>
              </div>

              <div v-if="form.errors.password" v-text="form.errors.password"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="password_confirmation" value="Confirm Password" class="mb-1"/>
                <label for="password_confirmation" class="input input-bordered input-primary flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                       class="w-4 h-4 opacity-70">
                    <path fill-rule="evenodd"
                          d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                          clip-rule="evenodd"/>
                  </svg>
                  <input id="password_confirmation"
                         v-model="form.password_confirmation" type="password" class="grow border-none" value=""
                         required/>
                </label>
              </div>
              <div v-if="form.errors.password_confirmation" v-text="form.errors.password_confirmation"
                   class="text-xs text-red-600 mt-1"></div>

              <div class="mt-4">
                <JetLabel for="invite_code" value="Invite Code"/>
                <JetInput
                    id="invite_code"
                    v-model="form.invite_code"
                    type="text"
                    class="mt-1 block w-full input input-bordered input-accent "
                    required
                />
              </div>

              <div v-if="form.errors.invite_code" v-text="form.errors.invite_code"
                   class="text-xs text-red-600 mt-1"></div>



              <div class="invite-code">
                <div class="mt-4 ml-4 border-t border-t-gray-200 text-center font-semibold">
                  <div>Need an invite code?</div>
                  <button
                      @click.prevent="appSettingStore.btnRedirect(`subscribe`)"
                      class="btn btn-md bg-blue-600 hover:bg-blue-700 text-white hover:cursor-pointer">Get an Invite
                    Code
                  </button>
                </div>
              </div>

              <!--            Jetstream/Fortify Multi-Auth: Roles, Permissions and Guards-->
              <!--            https://www.youtube.com/watch?v=NiQSNjWKLfU-->

              <!--            <div class="mt-4">-->
              <!--                <JetLabel  for="role_id" value="Register as" />-->
              <!--                <select name="role_id" v-model="form.role_id" class="mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">-->
              <!--                    <option value="2" selected>Viewer</option>-->
              <!--                    <option value="3">Creator</option>-->
              <!--                </select>-->
              <!--            </div>-->

              <!--            <div class="mt-4" v-if="form.role_id == 3">-->
              <!--                <JetLabel for="user_phone" value="Phone Number" />-->
              <!--                <JetInput-->
              <!--                    id="user_phone"-->
              <!--                    v-model="form.user_phone"-->
              <!--                    type="phone"-->
              <!--                    class="mt-1 block w-full"-->
              <!--                    required-->
              <!--                    autocomplete="new-password"-->
              <!--                />-->
              <!--            </div>-->


              <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <JetLabel for="terms">
                  <div class="flex items-center">
                    <input id="terms" v-model="form.terms" name="terms" type="checkbox"
                           class="checkbox checkbox-lg checkbox-accent" required/>
                    <div class="ml-2">
                      I agree to the <a :href="route('terms.show')" target="_blank"
                                        class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a>
                      and <a :href="route('policy.show')" target="_blank"
                             class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                    </div>
                  </div>
                </JetLabel>
                <div v-if="form.errors.terms" v-text="form.errors.terms"
                     class="text-xs text-red-600 mt-1"></div>
              </div>


              <div class="flex items-center justify-end mt-4">
                <Link :href="`/login`" class="underline text-sm text-gray-600 hover:text-gray-900">
                  Already registered?
                </Link>

                <JetButton class="btn ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Register
                </JetButton>
              </div>

            </form>

          </div>
        </div>
      </main>


      <Footer/>

    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Button from '@/Jetstream/Button.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.currentPage = 'register'
appSettingStore.setPrevUrl()

const props = defineProps({
  newsPeople: Array,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
  invite_code: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}

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
      videoPlayerStore.disposePlayer();
    }, 1000); // Delay the disposal by 1000 milliseconds (1 second)
  }
});

</script>
<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
  layout: NoLayout,
}
</script>