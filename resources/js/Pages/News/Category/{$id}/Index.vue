<template>
  <Head :title="`${newsCategory.name}`"/>
  <div id="topDiv"></div>
  <div class="flex flex-col min-h-screen bg-gray-50 text-black w-full overflow-x-hidden">

    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">
      <PublicNewsNavigationButtons/>

    </header>

    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <section>
          <NewsCategoryShowContainer :newsCategory="newsCategory"
                                     :newsStories="newsStories"
                                     :newsCategoryId="newsCategoryId"
                                     :newsSubCategories="newsSubCategories"/>
        </section>

      </div>
    </main>
  </div>
</template>

<script setup>
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import NewsCategoryShowContainer from '@/Components/Pages/News/Category/Layout/NewsCategoryShowContainer.vue'

const props = defineProps({
  newsCategory: Object,
  newsStories: Object,
  newsSubCategories: Array,
  newsCategoryId: Number,
})

usePageSetup(`news.category.${props.newsCategory.slug}`);

</script>
