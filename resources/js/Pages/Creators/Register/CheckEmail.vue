<template>

  <Head title="Check Email For Verification Link"/>
  <div class="h-[calc(100vh)]">
    <PublicResponsiveNavigationMenu />
    <PublicNavigationMenu />

    <div id="topDiv" class="bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar min-h-screen">

      <div class="flex flex-col mt-24 mb-8 text-gray-50 justify-center w-full text-center text-3xl font-semibold tracking-widest">
        <div><ApplicationLogo class="mx-auto w-1/2 lg:w-1/4 xl:w-1/6 mb-6"/></div>
        <div class="uppercase">
          Creator Registration
        </div>
      </div>
      <main class="pb-8 hide-scrollbar">
        <div class="mx-auto w-full md:w-3/4 max-w-96 px-4 border-b border-gray-800 hide-scrollbar">
          <div class="text-center mb-8 px-4">

            <div class="flex flex-col items-center">
              <span> Please check your email inbox for the email verification link.</span>
            </div>

          </div>

        </div>
      </main>

      <Footer />
    </div>

  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import ApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'creator.registration.checkEmail'

const props = defineProps({

})

</script>
