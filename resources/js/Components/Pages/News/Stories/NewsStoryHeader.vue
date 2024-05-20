<template>
  <div>
    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">
      <PublicNewsNavigationButtons class=""/>
    </header>

    <div class="w-full max-w-7xl mx-auto ">
      <Breadcrumbs :classType="'dark'"
                   :breadcrumbs="[{ text: 'News Stories', to: '/news' }, { text: $page.props.newsStory.category.name, to: `/news/category/${$page.props.newsStory.category.slug}` }, { text: $page.props.newsStory.subCategory.name, to: '' }]"/>
    </div>
  </div>
</template>
<script setup>
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs.vue'
</script>