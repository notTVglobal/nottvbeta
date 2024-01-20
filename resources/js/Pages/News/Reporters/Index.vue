<template>

  <Head :title="`News Reporters`"/>
  <div >
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    </div>

    <div class="bg-gray-900 place-self-center flex flex-col gap-y-3 w-full">
      <div id="topDiv" class="text-white px-5">

        <PublicNewsNavigationButtons/>

        <Breadcrumbs :breadcrumbs="[{ text: 'News', to: '/news' }, { text: 'Reporters', to: '/news/reporters' }]" />

        <div class="text-gray-50 text-center text-3xl font-semibold tracking-widest uppercase">News Reporters</div>

        <main class="pb-8">
          <div class="mx-auto px-4 border-b border-gray-800">
            <div class="flex bg-gray-200 p-5 my-10 mx-auto w-[calc(80vw)] text-gray-900 rounded justify-center">

              <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 w-full px-6">
                <div v-for="person in newsPeople" :key="person.id" class="text-center px-8 py-3">
                  <div class="hover:bg-gray-300 rounded-lg cursor-pointer pb-8">
                    <header class="pt-8 pb-6">
                      <Link :href="`/news/reporter/${person.id}`">
                        <h3>{{ person.name }}</h3>
                      </Link>
                    </header>
                    <main class="flex justify-center">
                      <Link :href="`/news/reporter/${person.id}`">
                        <img :src="person.profile_photo_url" alt="Profile Photo"
                             class="w-32 h-32 rounded-full object-cover">
                      </Link>
                    </main>
                    <footer class="mt-8">
                      Add other details here
                    </footer>
                  </div>
                </div>
              </section>

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
import { Link } from '@inertiajs/inertia-vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons'
import Footer from '@/Components/Global/Layout/Footer'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs'

const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'news.reporters'

const props = defineProps({
  newsPeople: Array,
})

</script>

<!--<script>-->
<!--import NoLayout from '@/Layouts/NoLayout'-->

<!--export default {-->
<!--  layout: NoLayout,-->
<!--}-->
<!--</script>-->
