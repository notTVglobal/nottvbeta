<template>

  <Head :title="$page.props.newsPerson.name"/>
  <div>
    <div>
      <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
    </div>

    <div class="bg-gray-900 place-self-center flex flex-col gap-y-3 w-full">
      <div id="topDiv" class="text-white px-5">

        <PublicNewsNavigationButtons/>

        <Breadcrumbs :breadcrumbs="[{ text: 'News', to: '/public/news' },
        { text: 'Reporters', to: '/public/news/reporters' }, { text: $page.props.newsPerson.name, to: '' }]"/>

        <div class="text-gray-50 text-center text-3xl font-semibold tracking-widest uppercase">Reporter Profile</div>

        <main class="pb-8">
          <div class="mx-auto px-4 border-b border-gray-800">
            <div class="bg-gray-200 mx-auto p-5 my-10 w-[calc(80vw)] px-10 2xl:px-96 py-10 text-gray-900 rounded">

              <div class="flex flex-col md:flex-row m-6">

                <div class="w-full flex justify-center lg:justify-start">
                  <img alt="News Reporter Profile Picture"
                       v-if="$page.props.newsPerson.profile_photo_path"
                       :src="`/storage/${$page.props.newsPerson.profile_photo_path}`"
                       class="h-64 w-96 object-cover shadow-lg rounded-lg">
                </div>

                <div class="w-full md:w-2/3 md:mt-0 md:ml-8 lg:w-2/3 xl:w-3/4 2xl:w-4/5 mt-4 lg:mt-0 lg:ml-8">
                  <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{
                      $page.props.newsPerson.name
                    }}</h1>
                  <div class="space-y-4 mt-4">
                    <!-- Sections for Biography, Past Stories, Contact -->
                    <section>
                      <h2 class="text-xl font-semibold">Biography</h2>
                      <p class="text-sm italic text-gray-700">
                        Biography text goes here.
                      </p>
                    </section>
                    <section>
                      <h2 class="text-xl font-semibold">Past Stories</h2>
                      <p class="text-sm italic text-gray-700">
                        Story details go here.
                      </p>
                    </section>
                    <section>
                      <h2 class="text-xl font-semibold">Contact</h2>
                      <p class="text-sm italic text-gray-700">
                        Contact information goes here.
                      </p>
                    </section>
                  </div>

                </div>



              <!--              <div class="flex flex-col md:flex-row m-6">-->
              <!--                <div class="md:w-1/3 flex justify-center md:justify-start">-->
              <!--                  <img alt="News Reporter Profile Picture"-->
              <!--                       v-if="$page.props.newsPerson.profile_photo_path"-->
              <!--                       :src="`/storage/${$page.props.newsPerson.profile_photo_path}`"-->
              <!--                       class="h-96 w-64 object-cover shadow-lg rounded-lg">-->
              <!--                </div>-->
              <!--                <div class="md:w-2/3 mt-4 md:mt-0 md:ml-8">-->
              <!--                  <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $page.props.newsPerson.name }}</h1>-->
              <!--                  <div class="space-y-4 mt-4">-->

              <!--                  </div>-->
              <!--                </div>-->
              <!--              </div>-->


              </div>
            </div>
          </div>
            <!--          </div>-->
        </main>
        <Footer/>
      </div>
    </div>
  </div>
</template>

<script setup>
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs'


const appSettingStore = useAppSettingStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'public.news.reporter.id'

</script>

<style scoped>

.breadcrumbLink {
  @apply text-blue-300 hover:text-blue-500
}

</style>