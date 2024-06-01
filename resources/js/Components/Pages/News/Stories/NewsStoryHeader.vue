<template>
  <div class="mb-4">
    <header class="place-self-center flex flex-col w-full text-black bg-gray-800">
      <PublicNewsNavigationButtons class=""/>
    </header>

    <div class="w-full max-w-7xl mx-auto">
      <Breadcrumbs :classType="'dark'"
                   :breadcrumbs="[{ text: 'News Stories', to: '/news' }, { text: $page.props.newsStory.category.name, to: `/news/category/${$page.props.newsStory.category.slug}` }, { text: $page.props.newsStory.subCategory.name, to: '' }]"/>
    </div>
    <div class="w-full max-w-7xl flex justify-end items-center mx-auto px-6">
      <BackButton />
    </div>
  </div>
</template>
<script setup>
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import Breadcrumbs from '@/Components/Global/Breadcrumbs/Breadcrumbs.vue'
import BackButton from '@/Components/Global/Buttons/BackButton.vue'
</script>