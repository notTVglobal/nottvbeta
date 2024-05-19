<template>
<div>
  <div class="mb-6">
    <label for="Title" class="block mb-2 text-sm font-bold text-gray-900">Title</label>
    <input
        type="text"
        v-model="newsStore.title"
        name="title"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        placeholder=""
    />
    <div v-if="newsStore.errors.title" class="text-sm text-red-600">
      {{ newsStore.errors.title }}
    </div>
  </div>

  <div class="mb-6">
    <label for="Content" class="block mb-2 text-sm font-bold text-gray-900">Content</label>
    <tip-tap-news-story-editor />
    <div v-if="newsStore.errors.body" class="text-sm text-red-600">
      {{ newsStore.errors.body }}
    </div>
  </div>
</div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import TipTapNewsStoryEditor from '@/Components/Global/TextEditor/TipTapNewsStoryEditor'

const newsStore = useNewsStore()

</script>