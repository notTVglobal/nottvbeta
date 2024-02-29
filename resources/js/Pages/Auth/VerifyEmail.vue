<template>
  <Head title="Email Verification"/>

  <div class="bg-gray-900 h-[calc(100vh)] hide-scrollbar">
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full h-16"/>
      <PublicResponsiveNavigationMenu/>
    </div>

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar">

      <div
          class="flex flex-col mt-36 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div>
          <Link :href="`/`">
            <ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/>
          </Link>
        </div>
      </div>

      <main class="pb-8 hide-scrollbar">
        <div class="mx-auto px-4 border-b border-gray-800 hide-scrollbar">


          <div class="mx-auto w-full sm:max-w-md mt-6 mb-64 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">


            <div class="mb-4 text-sm text-gray-600">
              Before continuing, will you please verify your email address by clicking on the link we just emailed to
              you? If you didn't receive the email, we will gladly send you another.
            </div>

            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
              A new verification link has been sent to the email address you provided in your profile settings.
            </div>

              <div class="mt-4 flex items-center justify-between">
                <JetButton @click.prevent="submit" :class="{ 'opacity-25': submitProcessingg }" :disabled="submitProcessing">
                  Resend Verification Email
                </JetButton>

                <div>

                  <button
                      @click.prevent="logout"
                      class="underline text-sm text-gray-600 hover:text-gray-900 ml-2"
                  >
                    Log Out
                  </button>
                </div>
              </div>
          </div>

        </div>
      </main>
      <Footer/>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useStoreReset } from "@/Utilities/StoreReset"
import { computed, onMounted, ref, watch } from 'vue'
import { Head, Link, usePage } from '@inertiajs/inertia-vue3'
import JetButton from '@/Jetstream/Button.vue'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'

const appSettingStore = useAppSettingStore()
const storeReset = useStoreReset()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'verify-email'

// const props = defineProps({
//   status: String,
// })

const submitProcessing = ref(false);

// Directly destructure 'flash' from usePage().props
const pageProps = usePage().props;

// Now, you can directly use 'flash' as it's already reactive
const verificationLinkSent = computed(() => pageProps.value.flash?.success === 'verification-link-sent');

// Watch for changes on the 'verificationLinkSent'
watch(verificationLinkSent, (newValue, oldValue) => {
  // If verification link is sent, stop processing
  if (newValue) {
    submitProcessing.value = false;
    // Additional logic for handling the verification link sent scenario
    console.log('Verification link sent');
  }
});

const submit = () => {
  submitProcessing.value = true;
  Inertia.post(route('verification.send'), {}, {
    // Using onSuccess callback to reset submitProcessing in case of success
    onSuccess: () => {
      submitProcessing.value = false;
    }
  });
}

const logout = () => {
  Inertia.post(route('logout'), {}, {
    onSuccess: () => {
      // Reset state inside onSuccess callback
      storeReset.resetAllStores();
      window.location.reload(); // Force a page reload
    }
  });
};
</script>
