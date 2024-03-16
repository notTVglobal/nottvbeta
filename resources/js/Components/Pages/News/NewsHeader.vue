<template>

  <div class="w-full mx-auto mb-3 pb-3 border-b border-gray-500 pt-6">


    <div class="w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 gap-2">
      <div class="text-4xl font-semibold text-center lg:text-left">
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
            <button
                @click="appSettingStore.btnRedirect(`/newsroom`)"
                class=""
                :class="getButtonClass('newsroom')">
              Stories
            </button>
          </li>
          <li>
            <button disabled
                    class=""
                    :class="getButtonClass('categories')">
              Categories
            </button>
          </li>
          <li>
            <button disabled
                    class=""
                    :class="getButtonClass('cities')">
              Cities
            </button>
          </li>
          <li>
            <button disabled
                    class=""
                    :class="getButtonClass('districts')">
              Districts
            </button>
          </li>
          <li>
            <button disabled
                    class=""
                    :class="getButtonClass('pressReleases')">
              Press Releases
            </button>
          </li>
          <li>
            <button disabled
                    class=""
                    :class="getButtonClass('calendar')">
              Calendar
            </button>
          </li>
          <li>
            <button
                @click="appSettingStore.btnRedirect(`/newsRssFeeds`)"
                class=""
                :class="getButtonClass('newsRssFeeds.index')">
              Feeds
            </button>
          </li>
          <li>
            <button
                @click="appSettingStore.btnRedirect(`/newsRssArchive`)"
                class=""
                :class="getButtonClass('newsRssArchive.index')">
              Archive
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
import { computed } from 'vue'

const appSettingStore = useAppSettingStore()

defineProps({
  search: String,
  can: Object,
})

const getButtonClass = (pageIdentifier) => {
  const baseClass = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition';
  const activeClass = 'border-indigo-400 text-gray-800 focus:outline-none focus:border-indigo-700';
  const inactiveClass = 'border-transparent text-black hover:text-blue-500 hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed disabled:border-none';

  return appSettingStore.currentPage === pageIdentifier
      ? `${baseClass} ${activeClass}`
      : `${baseClass} ${inactiveClass}`;
};
</script>
