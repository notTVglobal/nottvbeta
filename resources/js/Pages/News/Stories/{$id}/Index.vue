<template>
  <Head title="News Post"/>
  <div id="topDiv">

    <div class="flex flex-col min-h-screen w-full bg-gray-50 overflow-x-hidden" :class="marginTopClass">
      <div class="place-self-center flex flex-col gap-y-3 bg-gray-50 w-full">

        <PublicNavigationMenu v-if="!userStore.loggedIn" class="fixed top-0 w-full nav-mask"/>
        <PublicResponsiveNavigationMenu v-if="!userStore.loggedIn"/>

        <div class="bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
          <!--          <div class="text-center pt-6 text-3xl font-semibold leading-loose">NEWS</div>-->
<!--          <header class="place-self-center flex flex-col w-full text-black bg-gray-800">-->
<!--            <PublicNewsNavigationButtons :can="can" class=""/>-->
<!--          </header>-->


<!--          <div class="w-full">-->
<!--            <Breadcrumbs :classType="'dark'"-->
<!--                         :breadcrumbs="[{ text: 'News Stories', to: '/news' }, { text: $page.props.newsStory.category.name, to: `/news/category/${$page.props.newsStory.category.slug}` }, { text: $page.props.newsStory.subCategory.name, to: '' }]"/>-->
<!--          </div>-->


          <NewsStoryHeader :newsStory="newsStory" />


          <div v-if="userStore.loggedIn" class="w-full flex flex-row flex-wrap justify-end px-6 gap-2">
            <!--              This Back Button doesn't work...
                              because there is no prevUrl function that runs unless PageSetup() is called..
                              and PageSetup() is only called on LoggedIn pages.-->
            <!--              <BackButton v-if="!appSettingStore.loggedIn"/>-->

            <!--            <BackButton />-->
            <div class="">
              <button
                  v-if="props.can.viewNewsroom"
                  @click="appSettingStore.btnRedirect(`/newsroom`)"
                  class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"

              >Newsroom
              </button>
            </div>
            <div v-if="can.editNewsStory" class="ml-2">
              <button

                  @click="appSettingStore.btnRedirect(`/newsStory/${props.newsStory.slug}/edit`)"
                  class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >Edit
              </button>
            </div>
          </div>

          <NewsStoryMain :newsStory="newsStory"/>


<!--          <main class="w-full text-black pb-96">-->
<!--            &lt;!&ndash; Main Content &ndash;&gt;-->
<!--            <section class="w-full mx-auto px-4 md:py-6 md:mt-4">-->
<!--              <div-->
<!--                  :class="{'text-center': !appSettingStore.isSmallScreen, 'flex justify-center items-center': true, 'flex-col': appSettingStore.isSmallScreen, 'flex-row': !appSettingStore.isSmallScreen}">-->
<!--                <div v-if="newsStory.image" class="flex justify-center items-center md:max-w-sm">-->
<!--                  <SingleImageWithModal :image="newsStory.image" :alt="newsStory.title"-->
<!--                                        class="object-scale-down max-w-full px-3 mb-4 md:mb-0 mx-auto"/>-->
<!--                </div>-->
<!--                <div class="text-center md:text-left mb-4 md:ml-6">-->
<!--                  <h2 class="text-3xl font-semibold leading-tight">-->
<!--                    {{ newsStory.title }}-->
<!--                  </h2>-->
<!--                  <div class="">by {{ newsStory.newsPerson.name }}</div>-->

<!--                  <div v-if="newsStory.published_at" class="font-light mt-4">Published-->
<!--                    {{ userStore.formatDateTimeFullWithYearFromUtcToUserTimezone(newsStory.published_at) }} {{ userStore.timezoneAbbreviation }}-->
<!--                  </div>-->
<!--                  <div v-else-if="newsStory.status.name === 'Creators Only'" class="text-gray-700 italic">-->
<!--                    {{ newsStory.status.name }}-->
<!--                  </div>-->
<!--                  <div v-else class="font-light mt-4 italic">not published yet</div>-->
<!--                  <div v-if="newsStory.published_at < newsStory.updated_at" class="font-light">Last updated-->
<!--                    {{ userStore.formatDateTimeFullWithYearFromUtcToUserTimezone(newsStory.updated_at) }} {{ userStore.timezoneAbbreviation }}-->
<!--                  </div>-->

<!--                  <div class="pt-6">-->
<!--                    <div v-if="newsStory.newsCategory?.id" class="font-semibold text-orange-800">-->
<!--                      {{ newsStory.newsCategory.name }}-->
<!--                      <span v-if="newsStory.newsCategorySub?.id"><span-->
<!--                          class="text-black"> | </span>{{ newsStory.newsCategorySub.name }}</span>-->
<!--                    </div>-->
<!--                    <div v-if="newsStory.city?.id" class="font-semibold">{{ newsStory.city.name }}, <span-->
<!--                        class="font-medium text-gray-800">{{ newsStory.province?.name }}</span></div>-->
<!--                    <div-->
<!--                        v-if="newsStory.province?.id && !newsStory.city?.id && !newsStory.federalElectoralDistrict?.id && !newsStory.subnationalElectoralDistrict?.id"-->
<!--                        class="font-semibold">{{ newsStory.province.name }} &nbsp;&nbsp;<span-->
<!--                        class="text-xs font-medium text-gray-500 uppercase">Province</span></div>-->
<!--                    <div v-if="newsStory.federalElectoralDistrict?.id" class="font-semibold">-->
<!--                      {{ newsStory.federalElectoralDistrict.name }} &nbsp;&nbsp;<span-->
<!--                        class="text-xs font-medium text-gray-500 uppercase">Federal Electoral District</span></div>-->
<!--                    <div v-if="newsStory.subnationalElectoralDistrict?.id" class="font-semibold">-->
<!--                      {{ newsStory.subnationalElectoralDistrict.name }} &nbsp;&nbsp;<span-->
<!--                        class="text-xs font-medium text-gray-500 uppercase">Subnational Electoral District</span></div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--            </section>-->

<!--            <section class="w-max-w-full flex flex-wrap items-start justify-items-center px-6">-->
<!--              <div-->
<!--                  class="vw-100 overflow-x-none break-words flex flex-wrap px-6 border-b border-gray-200 news-content space-y-5">-->
<!--                &lt;!&ndash;                        <div v-html="'<pre>'+news.content+'</pre>'" class="text-left mb-6 leading-loose font-['monospace']">&ndash;&gt;-->
<!--                &lt;!&ndash;                <div v-html="news.content" class="text-left mb-6 text-xl px-16 leading-loose font-['monospace']">&ndash;&gt;-->
<!--                &lt;!&ndash;                </div>&ndash;&gt;-->
<!--              </div>-->
<!--              <div class="news-story w-full">-->
<!--                <tip-tap-news-story-render :content="newsStory.content"/>-->
<!--                &lt;!&ndash;                <TipTapViewOnly :content="props.newsStory.content_json" />&ndash;&gt;-->
<!--              </div>-->

<!--            </section>-->
<!--          </main>-->

        </div>
      </div>

      <Footer v-if="!userStore.loggedIn"/>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import { useNewsStore } from '@/Stores/NewsStore'
import TipTapNewsStoryRender from '@/Components/Global/TextEditor/TipTapNewsStoryRender.vue'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import NewsStoryHeader from '@/Components/Pages/News/Stories/NewsStoryHeader.vue'
import NewsStoryMain from '@/Components/Pages/News/Stories/NewsStoryMain.vue'
// import TipTapViewOnly from '@/Components/Global/TextEditor/TipTapViewOnly.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const newsStore = useNewsStore()

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
})

// Watch for changes in the loggedIn state of appSettingStore
watch(() => userStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn

  // Call usePageSetup if loggedIn is true
  if (loggedIn) {
    usePageSetup(`news.${props.newsStory.slug}`)
  }
})

const marginTopClass = computed(() => {
  return userStore.loggedIn ? '' : 'mt-16'
})

</script>

<style scoped>

</style>
