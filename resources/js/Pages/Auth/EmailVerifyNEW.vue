<template>

  <Head title="Email Verification"/>
  <div class="bg-gray-900 h-[calc(100vh)]">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div class="mt-36 text-gray-50 text-center text-3xl font-semibold tracking-widest">Check Your Email</div>

      <main class="pb-8  hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800  hide-scrollbar">

          <div class=" hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-3/4 max-w-96 text-gray-900 rounded">


            <div class="mb-4 text-sm text-gray-600">
              Before continuing, will you please verify your email address by clicking on the link we just emailed to
              you? If you didn't receive the email, we will gladly send you another.
            </div>

            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
              A new verification link has been sent to the email address you provided in your profile settings.
            </div>

            <form @submit.prevent="submit">
              <div class="mt-4 flex items-center justify-between">
                <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Resend Verification Email
                </JetButton>

                <div>

                  <Link
                      :href="route('logout')"
                      method="post"
                      as="button"
                      class="underline text-sm text-gray-600 hover:text-gray-900 ml-2"
                  >
                    Log Out
                  </Link>
                </div>
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
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'public.email.verify'

let props = defineProps({
  status: String,
})

const form = useForm()

const submit = () => {
  form.post(route('verification.send2'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>
