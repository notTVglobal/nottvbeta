<template>
  <div class="pt-8 max-w-7xl">
    <h1 class="text-4xl font-bold mb-6">{{ newsCategory.name }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Subcategories Section -->
      <div class="lg:col-span-1">
        <h2 class="text-2xl font-semibold mb-4">Subcategories</h2>
        <ul class="space-y-2">
          <li v-for="subCategory in newsCategory.subCategories" :key="subCategory.id">
            <Link :href="`/news/category/${subCategory.slug}`" class="text-blue-600 hover:underline">{{ subCategory.name }}</Link>
          </li>
        </ul>
      </div>

      <!-- Latest Stories Section -->
      <div class="lg:col-span-2">
        <h2 class="text-2xl font-semibold mb-4">Latest Stories</h2>
        <div v-if="newsStories?.data?.length" class="space-y-6">
          <div v-for="newsStory in newsStories.data" :key="newsStory.id" class="flex flex-col lg:flex-row bg-white p-4 rounded-lg shadow-md">


            <div class="card w-96 bg-gray-100 shadow-xl">
              <figure>
                <SingleImage :image="newsStory.image" :alt="newsStory.name" :class="`h-full object-cover`"/></figure>
              <div class="card-body">
                <h2 class="card-title"><Link :href="`/news/story/${newsStory.slug}`" class="text-black hover:text-gray-700">{{ newsStory.title }}</Link></h2>
                <p><ExpandableNewsStoryItem :content="newsStory.content" :hideButton="true"/></p>
                <div class="card-actions justify-center items-center">
                  <button class="btn btn-primary">Read Story</button>
                </div>
              </div>
            </div>


            <div class="lg:w-1/4 mb-4 lg:mb-0">
              <Link :href="`/news/story/${newsStory.slug}`">
                <SingleImage :image="newsStory.image" :alt="newsStory.name" class="w-full h-full rounded-lg object-cover"/>
              </Link>
            </div>
            <div class="lg:w-3/4 lg:pl-4">
              <h3 class="text-xl font-bold mb-2">
                <Link :href="`/news/story/${newsStory.slug}`" class="text-black hover:text-gray-700">{{ newsStory.title }}</Link>
              </h3>
              <p class="text-gray-600 mb-2">{{ newsStory.summary }}</p>
              <ExpandableNewsStoryItem :content="newsStory.content" :hideButton="true"/>
            </div>
          </div>
          <Pagination :data="newsStories"/>
        </div>
        <div v-else>
          <p class="text-gray-700">No news stories found for this category.</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { defineProps } from 'vue'

import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import ExpandableNewsStoryItem from '@/Components/Global/Text/ExpandableNewsStoryItem.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const props = defineProps({
  newsCategory: Object,
  newsStories: Object,
})

</script>
