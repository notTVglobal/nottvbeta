<template>
  <Head :title="`News Categories`"/>
  <div id="topDiv" ></div>
  <div class="flex flex-col min-h-screen bg-gray-50 text-black w-full overflow-x-hidden">

    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">

      <PublicNewsNavigationButtons/>

    </header>

    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <section>

          <NewsCategoryIndexContainer :newsCategories="newsCategories"/>

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
import NewsCategoryIndexContainer from '@/Components/Pages/News/Category/Layout/NewsCategoryIndexContainer.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

const props = defineProps({
  newsCategories: Object,
})

usePageSetup(`news.category.index`);

</script>
