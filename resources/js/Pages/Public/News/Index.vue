<template>

  <Head title="News"/>
  <div>
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    </div>

    <div class="mbplace-self-center flex flex-col gap-y-3 w-full">
      <div id="topDiv" class="bg-gray-900 text-white px-5">

        <PublicNewsNavigationButtons/>

        <div class="mt-4 text-gray-50 text-center text-3xl font-semibold tracking-widest uppercase">News Headlines</div>

        <main class="pb-8">
          <div class="mx-auto px-4 border-b border-gray-800 flex justify-center ">

<!--            <div class="bg-gray-200 mx-auto p-5 my-10 w-full text-gray-900 rounded">-->
              <div class="mb-20 bg-gray-200 p-5 my-10 w-[calc(80vw)] px-10 2xl:px-96 py-10 text-gray-900 rounded">
                <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">


              <div class="">


              </div>
                </div>
            </div>

          </div>
        </main>

        <Footer />

      </div>
    </div>
  </div>
</template>

<script setup>
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { Link } from '@inertiajs/inertia-vue3'
import Button from '@/Jetstream/Button.vue'
import { Inertia } from '@inertiajs/inertia'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'


const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'public.news.index'

const props = defineProps({
  newsPeople: Array,
})
</script>
