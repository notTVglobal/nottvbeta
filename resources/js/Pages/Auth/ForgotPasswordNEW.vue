<template>

  <Head title="Forgot Password"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div class="mt-36 text-gray-50 text-center text-3xl font-semibold tracking-widest">Forgot Password?</div>

      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-3/4 max-w-96 text-gray-900 rounded">


            <div class="mb-4 text-sm text-gray-600">
              Forgot your password? No problem. Just let us know your email address and we will email you a password
              reset link that will allow you to choose a new one.
            </div>

            <div class="mb-4 text-sm text-black bg-orange-500">
              tec21: 2024-01-19 The code for this is the same as "forgot-password.blade.php",
              it references 'PasswordResetLinkController', the only reference I can find to this
              is in the Fortify vendor package.
              It should be sending a $page.prop.status back with a message that can get displayed
              to the user "Password request email sent." Or however it's worded.
              // TO DO: Make this component work here and receive the status response to display
                        on the page.
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
              {{ status }}
            </div>


            <div class="">
              <JetValidationErrors class="mb-4"/>

              <form @submit.prevent="submit">
                <div>
                  <JetLabel for="email" value="Email"/>
                  <JetInput
                      id="email"
                      v-model="form.email"
                      type="email"
                      class="mt-1 block w-full text-gray-800"
                      required
                      autofocus
                  />
                </div>

                <div class="flex items-center justify-end mt-4">
                  <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                  </JetButton>
                </div>
              </form>


            </div>
          </div>

        </div>
      </main>

      <Footer/>

    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { Head, useForm } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import JetCheckbox from '@/Jetstream/Checkbox.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Button from '@/Jetstream/Button.vue'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'
import Login from '@/Pages/Auth/Login'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'public.forgotPassword'

let props = defineProps({
  status: String,
})

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'));
};
</script>
