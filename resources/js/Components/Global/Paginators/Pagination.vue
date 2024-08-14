<template>
  <div v-if="data?.last_page > 1" class="flex flex-wrap justify-center my-4 space-x-4 space-y-2">
    <div></div>
    <Component v-if="data && data?.links"
               :is="link?.url ? 'Link' : 'span'"
               v-for="(link, index) in data.links"
               :key="`${link?.url}-${index}`"
               :href="link?.url"
               @click.prevent="changePage(link)"
               v-html="link?.label"
               class="px-4 py-3 text-sm leading-4 h-fit"
               :class="{ 'text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400': link?.active,
                      'rounded mt-2 mr-0.5 text-gray-300 dark:text-gray-700': !link?.url,
                      'rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow': link?.url }"
    />
  </div>
</template>


<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

const emit = defineEmits(['page-change'])

defineProps({
  data: Object,
})

appSettingStore.savedScrollPosition = 0

const saveScrollPosition = () => {
  appSettingStore.savedScrollPosition = window.scrollY
}

// This function emits the page-change event when a link is clicked
const changePage = (link) => {
  if (link.url) {
    let page = new URL(link.url).searchParams.get('page')
    page = parseInt(page, 10) // Convert the page number to an integer
    console.log('Page to load:', page)
    emit('page-change', page)
  }
  saveScrollPosition()
}

window.scrollTo(0, appSettingStore.savedScrollPosition)

</script>


<!--class="px-1 text-gray-800 dark:text-gray-50 dark:hover:text-blue-400 hover:text-blue-400"-->
<!--:class="{ 'text-gray-300 dark:text-gray-700 dark:hover:text-gray-700 hover:text-gray-300': ! link.url, 'font-bold' : link.active }"-->
