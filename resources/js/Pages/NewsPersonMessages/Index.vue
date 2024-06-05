<template>
  <div>
    <Head title="News Message Inbox"/>

    <div class="place-self-center flex flex-col">
      <div id="topDiv" class="bg-white min-h-screen text-black dark:bg-gray-900 dark:text-gray-50">
        <NewsHeader>Newsroom</NewsHeader>
        <div class="w-full max-w-7xl mx-auto rounded-lg px-8 bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50 pb-24">
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
                  @click="prepareDeleteAll"
                  :disabled="isDeleteAllDisabled"
                  :class="[
                    'text-white font-semibold py-2 px-4 rounded-lg transition',
                    isDeleteAllDisabled ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700 cursor-pointer'
                  ]"
              >
                Delete All
              </button>
              <ConfirmationModal
                  :show="showModal"
                  title="Confirm Deletion"
                  message="Are you sure you want to delete all messages? This action cannot be undone."
                  @confirm="deleteAllMessages"
                  @cancelDelete="showModal = false"
              />
            </div>
            <div v-if="filteredMessages.length > 0">
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
                      <span v-html="message.message"/>
                    </div>
                    <div v-if="message.sender" class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                      <div v-if="message.sender.name">
                        <img v-if="message.sender.profile_photo_url" :src="message.sender.profile_photo_url" alt="Profile Photo"
                             class="w-10 h-10 rounded-full mr-2">
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
            <div v-else class="text-center py-20">
              <h2 class="text-2xl font-semibold mb-4">No Messages Yet 📭</h2>
              <p class="text-gray-600 dark:text-gray-300">Your inbox is currently empty. Start a new conversation by sending a message.</p>
            </div>
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
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useUserStore } from '@/Stores/UserStore'
import { computed, onMounted, ref } from 'vue'
import NewsHeader from '@/Components/Pages/News/NewsHeader.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import ConfirmationModal from '@/Components/Pages/NewsPersonMessages/ConfirmationModal.vue'

const newsPersonMessageStore = useNewsPersonMessageStore()
const userStore = useUserStore()
const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()

usePageSetup('newsPersonMessages.index')

let props = defineProps({
  messages: Object,
})

const showModal = ref(false)

const filteredMessages = computed(() => newsPersonMessageStore.messages)

function deleteMessage(messageId) {
  newsPersonMessageStore.deleteMessage(messageId)
}

const isDeleteAllDisabled = computed(() => {
  return newsPersonMessageStore.messages.length === 0;
});

const prepareDeleteAll = () => {
  newsPersonMessageStore.setDeleteAllTimestamp();
  showModal.value = true;
};


const deleteAllMessages = async () => {
  await newsPersonMessageStore.deleteAllMessages()
  showModal.value = false
  newsPersonMessageStore.reset()
  notificationStore.setToastNotification('All messages successfully deleted.', 'warning')

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