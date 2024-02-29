<template>
  <Head title="Forgot Password"/>

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
        <div class="mt-8 uppercase">Forgot Password?</div>
      </div>

      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <div
              class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-full md:w-3/4 max-w-96 text-gray-900 rounded">


            <div class="">
              <JetValidationErrors class="mb-4"/>

              <div class="mb-4 text-sm text-gray-600">
                Forgot your password? No problem. Just let us know your email address and we will email you a password
                reset link that will allow you to choose a new one.
              </div>

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
                      class="mt-1 block w-full text-gray-800"
                      required
                      autofocus
                  />
                </div>

                <div class="flex items-center justify-center mt-4">
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
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'forgot-password'

let props = defineProps({
  status: String,
})

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
}
</script>