<template>

  <div class="w-full mx-auto -mt-6 b-3 pb-3 border-b border-gray-500 pt-12 px-12 bg-yellow-950 text-white">

    <div class="w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 gap-x-2 gap-y-2">
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

    <div class="flex align-items-end mt-4 gap-2">
      <div class="w-full overflow-hidden shadow-sm sm:rounded-lg">
        <div class="w-full p-6 dark:bg-gray-900 border-b border-gray-200">
          <div class="w-full relative bg-gray-200 overflow-x-auto shadow-md sm:rounded-lg py-6 px-6">
            <ul class="flex flex-wrap justify-start ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 gap-x-2 gap-y-3 md:gap-x-8">
              <li v-for="button in buttons" :key="button.name" class="">
                <button
                    @click="button.enabled ? appSettingStore.btnRedirect(button.path) : null"
                    :class="getButtonClass(button.enabled)"
                    :disabled="!button.enabled">
                  {{ button.name }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>

  </div>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { reactive } from 'vue'

const appSettingStore = useAppSettingStore()

defineProps({
  search: String,
  can: Object,
})

// const getButtonClass = (pageIdentifier) => {
//   const baseClass = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition';
//   const activeClass = 'border-indigo-400 text-gray-800 focus:outline-none focus:border-indigo-700';
//   const inactiveClass = 'border-transparent text-black hover:text-blue-500 hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed disabled:border-none';
//
//   return appSettingStore.currentPage === pageIdentifier
//       ? `${baseClass} ${activeClass}`
//       : `${baseClass} ${inactiveClass}`;
// };

function getButtonClass(enabled) {
  return enabled
      ? 'py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition ease-in-out duration-150'
      : 'py-2 px-4 bg-gray-400 text-white font-semibold rounded-lg shadow-md cursor-not-allowed';
}

const buttons = reactive([
  { name: 'News Stories', path: '/newsroom', enabled: true },
  { name: 'News RSS Feeds', path: '/newsRssFeedItemsTemp', enabled: true },
  { name: 'News RSS Archive', path: '/newsRssArchive', enabled: true },
  { name: 'Categories', path: '/categories', enabled: false },
  { name: 'Cities', path: '/cities', enabled: false },
  { name: 'Districts', path: '/news-districts', enabled: true },
  { name: 'Press Releases', path: '/pressReleases', enabled: false },
  { name: 'Calendar', path: '/calendar', enabled: false },
]);
</script>
