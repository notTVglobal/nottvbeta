<template>

  <Head title="Create News Post"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <NewsCreateHeader/>

      <NewsSelectPersonContainer :can="can"/>
      <NewsCategoryCityContainer/>

      <NewsWriterComponent/>

      <div class="flex justify-end items-center">
        <transition name="fade">
          <div v-if="newsStore.showSaveMessage" class="save-message text-xs mr-2 text-green-500">
            Content cached
          </div>
        </transition>
        <CancelButton/>
        <button
            @click="newsStore.submit"
            class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none font-medium rounded-lg text-sm px-6 py-2.5 ml-2"
            :disabled="newsStore.processing"
            :class="{ 'opacity-25': newsStore.processing }"
        >
          Save
        </button>
        <JetValidationErrors class="ml-4"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onUnmounted } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'
import NewsCategoryCityContainer from '@/Components/Pages/News/NewsCategoryCityContainer.vue'
import NewsSelectPersonContainer from '@/Components/Pages/News/NewsSelectPersonContainer.vue'
import NewsCreateHeader from '@/Components/Pages/News/NewsCreateHeader.vue'
import NewsWriterComponent from '@/Components/Pages/News/NewsWriterComponent.vue'

usePageSetup('newsCreate')

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

let props = defineProps({
  can: Object,
  errors: Object,
})

newsStore.errors = props.errors; // This will contain the error messages

onUnmounted(() => {
  newsStore.reset()
})


</script>
