<template>

  <Head title="News"/>
  <div>

    <PublicNavigationMenu v-if="!appSettingStore.loggedIn" class="fixed top-0 w-full nav-mask"/>

    <div class="place-self-center flex flex-col gap-y-3 w-full">
      <div id="topDiv" class="bg-gray-900 text-white px-5">

        <PublicNewsNavigationButtons v-if="!appSettingStore.loggedIn"/>

        <div class="mt-4 text-gray-50 text-center text-3xl font-semibold tracking-widest uppercase">News Headlines</div>

        <main class="pb-8">
          <div class="mx-auto px-4 border-b border-gray-800 flex justify-center ">

            <!--            <div class="bg-gray-200 mx-auto p-5 my-10 w-full text-gray-900 rounded">-->
            <div class="mb-20 bg-gray-200 p-5 my-10 w-[calc(80vw)] px-10 2xl:px-96 py-10 text-gray-900 rounded">
              <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row justify-center">
                  <div class="text-2xl font-semibold">
                    Coming Soon.
                  </div>
                </div>
              </div>
            </div>

          </div>
        </main>

        <Footer  v-if="!appSettingStore.loggedIn"/>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'public.news.index'

const props = defineProps({
  newsPeople: Array,
})
</script>
