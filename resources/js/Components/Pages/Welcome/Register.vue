<template>
  <Transition
      enter-from-class="opacity-0 scale-125"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-125"
  >
    <div v-if="show" class="modal-mask overflow-auto py-64 hide-scrollbar">
      <div class="modal-container">
        <header class="flex uppercase text-sm font-semibold mb-2 text-center">
          <JetAuthenticationCardLogo class="max-w-[8rem]"/>
          <JetValidationErrors class="mb-4"/>
          <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
          </div>
        </header>

        <div class="py-3 text-gray-600">
          Please register for an account to join notTV.
        </div>
        <div class="py-3 text-black bg-white dark:bg-gray-800 dark:text-white">
          <form @submit.prevent="submit" ref="registrationForm">
            <div>
              <JetLabel for="name" value="Name"/>
              <JetInput
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full text-black bg-white dark:bg-gray-800 dark:text-white"
                  required
                  autofocus
                  autocomplete="name"
              />
            </div>

            <div class="mt-4">
              <JetLabel for="email" value="Email"/>
              <JetInput
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full text-black bg-white dark:bg-gray-800 dark:text-white"
                  required
              />
            </div>

            <div class="mt-4">
              <JetLabel for="password" value="Password"/>
              <JetInput
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="mt-1 block w-full text-black bg-white dark:bg-gray-800 dark:text-white"
                  required
                  autocomplete="new-password"
              />
            </div>

            <div class="mt-4">
              <JetLabel for="password_confirmation" value="Confirm Password"/>
              <JetInput
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  class="mt-1 block w-full text-black bg-white dark:bg-gray-800 dark:text-white"
                  required
                  autocomplete="new-password"
              />
            </div>

            <div class="mt-4">
              <JetLabel for="invite_code" value="Invite Code" class="text-green-800 font-semibold text-black bg-white dark:bg-gray-800 dark:text-white"/>
              <JetInput
                  id="invite_code"
                  v-model="form.invite_code"
                  type="invite_code"
                  class="mt-1 block w-full text-black text-xl h-14 px-4 border-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-black bg-white dark:bg-gray-800 dark:text-white"
                  required
              />
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
                  <JetCheckbox id="terms" v-model="form.terms" name="terms" required/>

                  <div class="ml-2">
                    I agree to the <a :href="route('terms.show')" target="_blank"
                                      class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a>
                    and <a :href="route('policy.show')" target="_blank"
                           class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                  </div>
                </div>
              </JetLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
              <!--                        <button @click="showLogin" class="underline text-sm text-gray-600 hover:text-gray-900">-->
              <!--                            Already registered?-->
              <!--                        </button>-->

              <JetButton class="ml-4" v-on:keyup.enter="submit" :class="{ 'opacity-25': form.processing }"
                         :disabled="form.processing">
                Register
              </JetButton>
            </div>
          </form>
        </div>

        <footer>
          <div class="flex flex-wrap justify-between modal-footer">
            <div class="w-fit">
              <button
                  @click="clearForm"
                  class="w-fit bg-gray-300 p-2 rounded-md hover:bg-gray-400 hover:text-gray-800"
              >Cancel
              </button>
            </div>
            <div class="flex flex-wrap justify-end mt-2 ml-4 font-semibold">
              <div>For a chance to get an invite code&nbsp;</div><div><a
                href="https://not.tv/subscribe" target="_blank" class="font-bold text-blue-600 hover:text-blue-400 hover:cursor-pointer">subscribe
              to our newsletter</a></div>
            </div>
          </div>
        </footer>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'

const welcomeStore = useWelcomeStore()

defineProps({
  status: String,
  show: Boolean
});

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: true,
  invite_code: '',
});

function clearForm() {
  form.reset();
  welcomeStore.showRegister = false;
}

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};

function showLogin() {
  form.reset();
  welcomeStore.showLogin = true;
  welcomeStore.showRegister = false;
}

</script>

<style scoped>
.modal-mask {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: grid;
  place-items: center;
  z-index: 200;
}

.modal-container {
  background: white;
  color: black;
  padding: 1rem;
  width: 80vw;
  max-width: 500px;
  border-radius: 8px;
}

.modal-footer {
  border-top: 1px solid #ddd;
  margin-top: 1rem;
  padding-top: 0.5rem;
  font-size: .8rem;
}
</style>
