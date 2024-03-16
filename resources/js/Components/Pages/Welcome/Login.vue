<template>
  <Transition
      enter-from-class="opacity-0 scale-125"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-125"
  >
    <div v-if="show" class="modal-mask overflow-auto py-32 hide-scrollbar">
      <div class="modal-container">
        <header class="flex justify-center uppercase text-sm font-semibold mb-2 text-center">
          <JetAuthenticationCardLogo class="max-w-[8rem]"/>
          <JetValidationErrors class="mb-4"/>
          <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
          </div>
        </header>

        <div class="text-center mt-4 py-3 text-gray-600">
          Please log in to watch notTV and chat.
        </div>
        <div class="py-3 px-6">
          <form @submit.prevent="submit">
            <div class="w-full">
              <label for="email" class="input input-bordered input-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                     class="w-4 h-4 opacity-70">
                  <path
                      d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"/>
                  <path
                      d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"/>
                </svg>
                <input id="email"
                       v-model="form.email" type="email" class="grow border-none w-full" placeholder="Email" required autofocus/>
              </label>
            </div>

            <div class="w-full mt-4">
              <label for="password" class="input input-bordered input-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                     class="w-4 h-4 opacity-70">
                  <path fill-rule="evenodd"
                        d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                        clip-rule="evenodd"/>
                </svg>
                <input id="password"
                       v-model="form.password" type="password" class="grow border-none w-full" value="" required/>
              </label>
            </div>

            <div class="block mt-4">
              <label class="flex items-center">
                <JetCheckbox v-model:checked="form.remember" name="remember"/>
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
              </label>
            </div>

            <div class="flex flex-wrap-reverse items-center justify-end mt-4">
              <Link :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                Forgot your password?
              </Link>

              <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Log in
              </JetButton>
            </div>
          </form>
        </div>

        <footer>
          <div class="flex justify-between modal-footer">
            <button
                @click="clearForm"
                class="bg-gray-300 p-2 rounded-md hover:bg-gray-400 hover:text-gray-800"
            >Cancel
            </button>
            <div class="mt-2">
              Need to
              <button @click="showRegister" class="text-blue-800 hover:text-blue-600">register</button>
              for an account?
            </div>
          </div>
        </footer>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import { useUserStore } from "@/Stores/UserStore"
import { useForm } from '@inertiajs/inertia-vue3'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'

const welcomeStore = useWelcomeStore()
const userStore = useUserStore()

defineProps({
  canResetPassword: Boolean,
  status: String,
  show: Boolean
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('login'), {
    onFinish: () => {
      form.reset('password'); // Reset the password field after submission
      console.log('get user data on Login')
      userStore.fetchUserData() // Fetch user data after successful login
          .then(() => {
            // Handle any post-fetch logic here, e.g., redirecting the user or updating the UI
            // Optionally, update timezone or perform other actions after login
            // const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            // userStore.updateUserTimezone(timezone);
          })
          .catch(error => {
            console.error("Failed to fetch user data:", error);
            // Handle any errors in fetching user data here
          });
    }
  });
};

function clearForm() {
  form.reset();
  welcomeStore.showLogin = false;
}

function showRegister() {
  form.reset();
  welcomeStore.showLogin = false;
  welcomeStore.showRegister = true;
}

</script>

<style scoped>
.modal-mask {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: grid;
  place-items: center;
  z-index: 100;
}

.modal-container {
  background: white;
  color: black;
  padding: 1rem;
  width: 80vw;
  max-width: 450px;
  border-radius: 8px;
}

.modal-footer {
  border-top: 1px solid #ddd;
  margin-top: 1rem;
  padding-top: 0.5rem;
  font-size: .8rem;
}
</style>
