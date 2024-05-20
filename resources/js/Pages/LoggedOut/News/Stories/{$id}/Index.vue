<template>
  <Head :title="`${newsStory.title}`"/>
  <div id="topDiv">

    <div class="flex flex-col h-screen w-full bg-gray-50 overflow-x-hidden overflow-y-auto mt-16">
      <div class="place-self-center flex flex-col gap-y-3 bg-gray-50 w-full">
        <div class="bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
          <!--          <div class="text-center pt-6 text-3xl font-semibold leading-loose">NEWS</div>-->
          <header class="place-self-center flex flex-col w-full text-black bg-gray-800">
            <PublicNewsNavigationButtons class=""/>
          </header>

          <PublicNavigationMenu class="fixed top-0 w-full nav-mask"/>
          <PublicResponsiveNavigationMenu/>
          <div class="w-full">
            <Breadcrumbs :classType="'dark'"
                         :breadcrumbs="[{ text: 'News Stories', to: '/news' }, { text: $page.props.newsStory.category.name, to: `/news/category/${$page.props.newsStory.category.slug}` }, { text: $page.props.newsStory.subCategory.name, to: '' }]"/>
          </div>


          <main class="w-full text-black pb-96">
            <!-- Main Content -->
            <section class="w-full mx-auto text-center flex justify-center px-4 md:py-6 md:mt-4">
              <div class="flex xl:flex-row flex-wrap-reverse">
                <div class="w-full flex justify-center md:items-start">
                  <!--            <img :src="`/storage/images/${props.image}`" class="object-scale-down md:max-w-sm px-6 mb-4 mx-auto">-->
                  <SingleImage :image="newsStory.image" :alt="''"
                               :class="'object-scale-down md:max-w-sm px-3 mb-4 mx-auto'"/>
                </div>
                <div class="text-center lg:text-left mb-4 lg:ml-6">
                  <h2 class="text-3xl font-semibold leading-tight">
                    {{ newsStory.title }}
                  </h2>
                  <div class="">by {{ newsStory.newsPerson.name }}</div>

                  <div v-if="newsStory.published_at" class="font-light mt-4">Published
                    {{ userStore.formatDateTimeFullWithYearFromUtcToUserTimezone(newsStory.published_at) }}
                    {{ userStore.timezoneAbbreviation }}
                  </div>
                  <div v-else-if="newsStory.status === 'Creators Only'" class="text-gray-700 italic">
                    {{ newsStory.status.name }}
                  </div>
                  <div v-else class="font-light mt-4 italic">not published yet</div>
                  <div v-if="newsStory.published_at < newsStory.updated_at" class="font-light">Last updated
                    {{ userStore.formatDateTimeFullWithYearFromUtcToUserTimezone(newsStory.updated_at) }}
                    {{ userStore.timezoneAbbreviation }}
                  </div>

                  <div class="pt-6">
                    <div v-if="newsStory.newsCategory?.id" class="font-semibold text-orange-800">
                      {{ newsStory.newsCategory.name }}
                      <span v-if="newsStory.newsCategorySub?.id"><span
                          class="text-black"> | </span>{{ newsStory.newsCategorySub.name }}</span>
                    </div>
                    <div v-if="newsStory.city?.id" class="font-semibold">{{ newsStory.city.name }}, <span
                        class="font-medium text-gray-800">{{ newsStory.province?.name }}</span></div>
                    <div
                        v-if="newsStory.province?.id && !newsStory.city?.id && !newsStory.federalElectoralDistrict?.id && !newsStory.subnationalElectoralDistrict?.id"
                        class="font-semibold">{{ newsStory.province.name }} &nbsp;&nbsp;<span
                        class="text-xs font-medium text-gray-500 uppercase">Province</span></div>
                    <div v-if="newsStory.federalElectoralDistrict?.id" class="font-semibold">
                      {{ newsStory.federalElectoralDistrict.name }} &nbsp;&nbsp;<span
                        class="text-xs font-medium text-gray-500 uppercase">Federal Electoral District</span></div>
                    <div v-if="newsStory.subnationalElectoralDistrict?.id" class="font-semibold">
                      {{ newsStory.subnationalElectoralDistrict.name }} &nbsp;&nbsp;<span
                        class="text-xs font-medium text-gray-500 uppercase">Subnational Electoral District</span></div>
                  </div>

                </div>
              </div>
            </section>

            <section class="w-max-w-full flex flex-wrap items-start justify-items-center px-6">
              <div
                  class="vw-100 overflow-x-none break-words flex flex-wrap px-6 border-b border-gray-200 news-content space-y-5">
                <!--                        <div v-html="'<pre>'+news.content+'</pre>'" class="text-left mb-6 leading-loose font-['monospace']">-->
                <!--                <div v-html="news.content" class="text-left mb-6 text-xl px-16 leading-loose font-['monospace']">-->
                <!--                </div>-->
              </div>
              <div class="news-story">
                <TipTapRender :content="props.newsStory.content"/>
                <!--                <TipTapViewOnly :content="props.newsStory.content_json" />-->
              </div>

            </section>
          </main>

        </div>
      </div>

      <Footer/>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs.vue'
import BackButton from '@/Components/Global/Buttons/BackButton.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import { useNewsStore } from '@/Stores/NewsStore'
import TipTapRender from '@/Components/Global/TextEditor/TipTapNewsStoryRender.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
// import TipTapViewOnly from '@/Components/Global/TextEditor/TipTapViewOnly.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const newsStore = useNewsStore()
const videoPlayerStore = useVideoPlayerStore()

let props = defineProps({
  newsStory: Object,
  can: Object,
})

appSettingStore.currentPage = `/news/story/${props.newsStory.slug}`
appSettingStore.setPrevUrl()

onMounted(() => {
  newsStore.reset()
  // newsStore.content_json = JSON.parse(props.newsStory.content_json)
  newsStore.content = props.newsStory.content
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }
})

// Watch for changes in the loggedIn state of appSettingStore
// watch(() => userStore.loggedIn, (loggedIn) => {
//   appSettingStore.noLayout = !loggedIn;
//
//   // Call usePageSetup if loggedIn is true
//   if (loggedIn) {
//     usePageSetup(`news.${props.newsStory.slug}`);
//   }
// });


</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

