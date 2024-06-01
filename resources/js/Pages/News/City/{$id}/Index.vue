<template>
  <Head :title="`${newsCity.name}`"/>
  <div id="topDiv" ></div>
  <div class="flex flex-col min-h-screen bg-gray-50 text-black w-full overflow-x-hidden">

    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">

      <PublicNewsNavigationButtons/>

    </header>

    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <section>

          <NewsCityShowContainer :newsCity="newsCity"
                                 :newsStories="newsStories"
                                 :newsCategoryId="newsCategoryId"
                                 :newsSubCategories="newsSubCategories"/>

        </section>

      </div>
    </main>


  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import NewsCityShowContainer from '@/Components/Pages/News/City/Layout/NewsCityShowContainer.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

const props = defineProps({
  newsCity: Object,
  newsStories: Object,
  newsSubCategories: Array,
  newsCategoryId: Number
})

usePageSetup(`news.city.${props.newsCity.slug}`);

</script>
