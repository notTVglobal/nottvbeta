<template>
  <div>
    <Head title="News Message Inbox"/>

    <div class="place-self-center flex flex-col">
      <div id="topDiv" class="bg-white h-screen text-black dark:bg-gray-900 dark:text-gray-50">
        <NewsHeader>Newsroom</NewsHeader>
        <div class="w-full max-w-7xl px-8 bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
          <div class="py-8 px-4 lg:px-16">
            <h1 class="text-3xl font-bold mb-6">Message Details</h1>
            <div class="mb-6">
              <h2 class="text-2xl font-semibold mb-4">{{ newsPersonMessage.subject || 'No Subject' }}</h2>
              <div v-if="newsPersonMessage.sender" class="text-lg mb-2">
                <div class="flex items-center">
                  <img :src="newsPersonMessage.sender.profile_photo_url" alt="Profile Photo" class="w-12 h-12 rounded-full mr-4">
                  <div>
                    <p><span class="font-semibold">Sender:</span> {{ newsPersonMessage.sender.name }}</p>
                    <p v-if="newsPersonMessage.sender.roles.length"><span class="font-semibold">Roles:</span> {{ newsPersonMessage.sender.roles.join(', ') }}</p>
                    <p v-if="newsPersonMessage.sender.creator_slug"><span class="font-semibold">Creator Slug:</span> {{ newsPersonMessage.sender.creator_slug }}</p>
                    <p v-if="newsPersonMessage.sender.email"><span class="font-semibold">Email:</span> {{ newsPersonMessage.sender.email }}</p>
                    <p v-if="newsPersonMessage.sender.phone"><span class="font-semibold">Phone:</span> {{ newsPersonMessage.sender.phone }}</p>
                  </div>
                </div>
              </div>
              <p class="text-lg mt-4">
                <span class="font-semibold">Message:</span>
              </p>
              <p class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow mt-2">{{ newsPersonMessage.message }}</p>
            </div>
            <div class="flex space-x-4">
              <button
                  @click="appSettingStore.btnRedirect('/news-person-messages')"
                  class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition"
              >
                Back to Inbox
              </button>
              <button
                  @click="deleteMessage(newsPersonMessage.id)"
                  class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center"
              >
                <font-awesome-icon icon="fa-trash-can" class="mr-2" />
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { defineProps, onMounted } from 'vue'
import { useNewsPersonMessageStore } from '@/Stores/NewsPersonMessageStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeader from '@/Components/Pages/News/NewsHeader.vue'

const newsPersonMessageStore = useNewsPersonMessageStore()
const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const props = defineProps({
  newsPersonMessage: Object,
})

usePageSetup('newsPersonMessages.' + props.newsPersonMessage.id)

function deleteMessage(messageId) {
  newsPersonMessageStore.deleteMessage(messageId)
}

onMounted(() => {
  newsPersonMessageStore.updateMessage(props.newsPersonMessage)
})
</script>
