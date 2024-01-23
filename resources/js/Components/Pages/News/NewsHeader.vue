<template>

  <div class="w-full mx-auto mb-3 pb-3 border-b border-gray-500 pt-6">


    <div class="w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 gap-2">
      <div class="text-3xl font-semibold text-center lg:text-left">
        <slot/>
      </div>
      <div>
        <button
            @click="appSettingStore.btnRedirect('/dashboard')"
            class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        >Dashboard
        </button>
      </div>

    </div>

    <div class="flex justify-between align-items-end mt-4">
      <div>
        <ul class="flex flex-wrap ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 space-x-8">
          <li>
            <button disabled
                    class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">
              Stories
            </button>
          </li>
          <li>
            <button disabled
                    class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">
              Categories
            </button>
          </li>
          <li>
            <button disabled
                    class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">
              Cities
            </button>
          </li>
          <li>
            <button disabled
                    class="text-gray-700 dark:text-gray-50 disabled:text-gray-300 disabled:cursor-not-allowed hover:text-blue-500">
              Regions
            </button>
          </li>
          <li>
            <button
                @click="appSettingStore.btnRedirect(`/newsRssFeeds`)"
                class="text-gray-700 dark:text-gray-50 hover:text-blue-500"
            >Feeds
            </button>
          </li>
        </ul>
      </div>
      <div>
        <NewsHeaderButtons :can="can"/>
      </div>
    </div>

  </div>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import NewsHeaderButtons from "@/Components/Pages/News/NewsHeaderButtons"

const appSettingStore = useAppSettingStore()

defineProps({
  search: String,
  can: Object,
})
</script>
