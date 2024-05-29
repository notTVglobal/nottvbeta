<template>
  <main class="w-full text-black pb-96">
    <!-- Main Content -->
    <section class="w-full max-w-7xl mx-auto px-4 md:py-6 md:mt-4 gap-4">
      <div
          :class="{'text-center': !appSettingStore.isSmallScreen, 'flex justify-center items-center': true, 'flex-col': appSettingStore.isSmallScreen, 'flex-row': !appSettingStore.isSmallScreen}">
        <div v-if="newsStory.image" class="flex justify-center items-center md:max-w-sm">
          <SingleImageWithModal :image="newsStory.image" :alt="newsStory.title"
                                class="object-scale-down max-w-full px-3 mb-4 md:mb-0 mx-auto"/>
        </div>
        <div class="text-center md:text-left mb-4 md:ml-6">
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

          <div class="mt-6" :class="{'w-full flex justify-center': appSettingStore.isSmallScreen}">
            <ShareButton :model="newsStory" />
          </div>

        </div>

      </div>
    </section>

    <section class="w-full max-w-7xl mx-auto flex flex-wrap items-center justify-center px-6">
      <div
          class="vw-100 overflow-x-none break-words flex flex-wrap px-6 border-b border-gray-200 news-content space-y-5">
        <!--                        <div v-html="'<pre>'+news.content+'</pre>'" class="text-left mb-6 leading-loose font-['monospace']">-->
        <!--                <div v-html="news.content" class="text-left mb-6 text-xl px-16 leading-loose font-['monospace']">-->
        <!--                </div>-->
      </div>
      <div class="news-story">
        <TipTapRender :content="newsStory.content"/>
        <!--                <TipTapViewOnly :content="props.newsStory.content_json" />-->
      </div>

    </section>

  </main>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import TipTapRender from '@/Components/Global/TextEditor/TipTapNewsStoryRender.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import { computed } from 'vue'
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const props = defineProps({
  newsStory: Object,
})
</script>