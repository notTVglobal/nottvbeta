<template>
  <div v-if="userStore.loggedIn" class="w-full flex flex-row flex-wrap justify-end px-6 gap-2 mb-2">
    <!--              This Back Button doesn't work...
                      because there is no prevUrl function that runs unless PageSetup() is called..
                      and PageSetup() is only called on LoggedIn pages.-->
    <!--              <BackButton v-if="!appSettingStore.loggedIn"/>-->

    <!--            <BackButton />-->
    <div class="">
      <button
          v-if="props.can.viewNewsroom"
          @click="appSettingStore.btnRedirect(`/newsroom`)"
          class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"

      >Newsroom
      </button>
    </div>
    <div v-if="can.editNewsStory" class="ml-2">
      <button

          @click="appSettingStore.btnRedirect(`/newsStory/${props.newsStory.slug}/edit`)"
          class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
      >Edit
      </button>
    </div>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const props = defineProps({
  can: Object,
  newsStory: Object,
})
</script>