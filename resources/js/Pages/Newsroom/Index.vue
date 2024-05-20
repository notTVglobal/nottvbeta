<template>
  <div>
    <Head title="Newsroom"/>

    <div class="place-self-center flex flex-col">
      <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-gray-50 mb-10">

        <NewsHeader :can="can">Newsroom</NewsHeader>

        <Messages v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

        <NewsStoriesContainer :newsStories="newsStories" :can="can" :filters="filters"/>

        <WelcomeToTheNewsroom />

      </div>
    </div>

  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useNewsStore } from '@/Stores/NewsStore'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Messages from '@/Components/Global/Modals/Messages'
import WelcomeToTheNewsroom from '@/Components/Pages/Newsroom/Layout/WelcomeToTheNewsroom.vue'
import NewsStoriesContainer from '@/Components/Pages/Newsroom/Layout/NewsStoriesContainer.vue'
import { onMounted } from 'vue'

usePageSetup('newsroom')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const newsStore = useNewsStore()

let props = defineProps({
  filters: Object,
  can: Object,
  newsStories: Object,
  newsStoryStatuses: Object,
})

onMounted(() => {
  newsStore.newsStoryStatuses = props.newsStoryStatuses
})

</script>
