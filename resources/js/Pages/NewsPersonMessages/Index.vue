<template>
  <div>
    <Head title="News Message Inbox"/>

    <div class="place-self-center flex flex-col">
      <div id="topDiv" class="bg-white min-h-screen text-black dark:bg-gray-900 dark:text-gray-50">
        <NewsHeader>Newsroom</NewsHeader>
        <div class="w-full max-w-7xl px-8 bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50 pb-24">
          <div class="py-8 px-4 lg:px-16">
            <h1 class="text-3xl font-bold mb-6">Your Personal News Inbox</h1>
            <div class="mb-6 flex space-x-4">
              <button
                  @click="appSettingStore.btnRedirect('/news-person-messages/create')"
                  class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition"
              >
                Create New Message
              </button>
              <button
                  @click="deleteAllMessages"
                  class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
              >
                Delete All
              </button>
            </div>
            <ul class="space-y-4">
              <li
                  v-for="message in filteredMessages"
                  :key="message.id"
                  :class="[
                  'relative border-b border-gray-300 pb-4 flex items-center p-4 rounded-lg shadow hover:shadow-md transition',
                  message.read_at ? 'bg-white dark:bg-gray-700' : 'bg-blue-100 dark:bg-blue-900'
                ]"
              >
                <Link
                    :href="`/news-person-messages/${message.id}`"
                    class="flex-grow mr-4"
                >
                  <div class="flex justify-between items-center">
                    <div class="text-lg font-semibold">{{ message.subject || 'No Subject' }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      <ConvertDateTimeToTimeAgo :datetime="message.created_at" :timezone="userStore.timezone"/>
                    </div>
                  </div>
                  <div class="text-gray-600 dark:text-gray-300 mt-2 message-content">
                    {{ message.message }}
                  </div>
                  <div v-if="message.sender" class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <div>
                      <img :src="message.sender.profile_photo_url" alt="Profile Photo" class="w-10 h-10 rounded-full mr-2">
                      <span>{{ message.sender.name }}</span>
                    </div>
                    <div v-if="message.sender.roles.length">
                      <span>Roles: {{ message.sender.roles.join(', ') }}</span>
                    </div>
                    <div v-if="message.sender.creator_slug">
                      <span>Creator Slug: {{ message.sender.creator_slug }}</span>
                    </div>
                    <div v-if="message.sender.email">
                      <span>Email: {{ message.sender.email }}</span>
                    </div>
                    <div v-if="message.sender.phone">
                      <span>Phone: {{ message.sender.phone }}</span>
                    </div>
                  </div>
                </Link>
                <div class="absolute bottom-4 right-4">
                  <font-awesome-icon
                      icon="fa-trash-can"
                      class="text-xl text-red-600 hover:text-red-800 hover:cursor-pointer transition"
                      @click.prevent="deleteMessage(message.id)"
                  />
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useNewsPersonMessageStore } from '@/Stores/NewsPersonMessageStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { computed, onMounted } from 'vue'
import NewsHeader from '@/Components/Pages/News/NewsHeader.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'

const newsPersonMessageStore = useNewsPersonMessageStore()
const userStore = useUserStore()
const appSettingStore = useAppSettingStore()

usePageSetup('newsPersonMessages.index')

let props = defineProps({
  messages: Object,
})

const filteredMessages = computed(() => newsPersonMessageStore.messages)

function deleteMessage(messageId) {
  newsPersonMessageStore.deleteMessage(messageId)
}

function deleteAllMessages() {
  newsPersonMessageStore.deleteAllMessages()
}

onMounted(() => {
  // newsPersonMessageStore.fetchMessages()
  newsPersonMessageStore.initializeNewsPersonMessageStore(props.messages)
})

</script>
<style scoped>
.message-content {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  max-height: 3rem; /* Approximately 2 lines */
}
</style>