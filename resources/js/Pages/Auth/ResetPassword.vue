<template>
  <Head :title="`Reset Password`"/>

  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">


      <div
          class="flex flex-col mt-36 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div>
          <Link :href="`/`"><ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/></Link>
        </div>
        <div class="mt-8 uppercase">Reset Password</div>
      </div>


      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <div
              class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">


            <div class="">


              <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
              </div>


              <form @submit.prevent="submit">
                <div>
                  <JetLabel for="email" value="Email"/>
                  <JetInput
                      id="email"
                      v-model="form.email"
                      type="email"
                      class="mt-1 block w-full"
                      required
                  />
                </div>

                <div class="mt-4">
                  <JetLabel for="password" value="Password"/>
                  <JetInput
                      id="password"
                      v-model="form.password"
                      type="password"
                      class="mt-1 block w-full"
                      required
                      autofocus
                      autocomplete="new-password"
                  />
                </div>

                <div class="mt-4">
                  <JetLabel for="password_confirmation" value="Confirm Password"/>
                  <JetInput
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      type="password"
                      class="mt-1 block w-full"
                      required
                      autocomplete="new-password"
                  />
                </div>

                <div v-if="form.errors.password_confirmation" class="my-2 text-red-700">{{ form.errors.password_confirmation[0] }}</div>
                <JetValidationErrors class="mt-2 mb-4"/>

                <div class="flex items-center justify-end mt-4">
                  <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password
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
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { Head, useForm } from '@inertiajs/vue3'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'reset-password'

const props = defineProps({
  email: String,
  token: String,
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  // Check if password and password confirmation match
  if (form.password !== form.password_confirmation) {
    // Handle the mismatch, e.g., by setting an error message
    form.errors.password_confirmation = ['The password confirmation does not match.'];
    return; // Prevent form submission
  }

  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>
