<template>
  <div>
    <Head title="News Message Inbox"/>
    <div class="place-self-center flex flex-col">
      <div id="topDiv" class="bg-white h-screen text-black dark:bg-gray-900 dark:text-gray-50">
        <NewsHeader>Newsroom</NewsHeader>
        <div class="w-full max-w-7xl px-8 bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
          <div class="py-8 px-4 lg:px-16">
            <h1 class="text-3xl font-bold mb-6">Create News Person Message</h1>
            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <label for="recipient_id" class="block text-lg font-medium mb-2">Recipient</label>
                <NewsPersonSelector @select="setRecipientId" />
              </div>
              <div>
                <label for="subject" class="block text-lg font-medium mb-2">Subject (optional)</label>
                <input
                    v-model="form.subject"
                    id="subject"
                    type="text"
                    class="w-full p-4 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-black dark:text-gray-50"
                />
              </div>
              <div>
                <label for="message" class="block text-lg font-medium mb-2">Message</label>
                <textarea
                    v-model="form.message"
                    id="message"
                    class="w-full p-4 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-black dark:text-gray-50"
                    rows="6"
                ></textarea>
              </div>
              <div class="flex space-x-4">
                <button
                    type="submit"
                    class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                >
                  Send
                </button>
                <button
                    type="button"
                    @click="$inertia.get('/news-person-messages')"
                    class="w-full py-3 px-6 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useForm } from '@inertiajs/vue3'
import { useNewsPersonMessageStore } from '@/Stores/NewsPersonMessageStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import NewsPersonSelector from '@/Components/Pages/NewsPersonMessages/NewsPersonSelector.vue'
import NewsHeader from '@/Components/Pages/News/NewsHeader.vue'

const newsPersonMessageStore = useNewsPersonMessageStore()
const notificationStore = useNotificationStore()

usePageSetup('newsPersonMessages.create')

let form = useForm({
  recipient_id: '',
  subject: '',
  message: '',
})

const setRecipientId = (person) => {
  form.recipient_id = person
}

function submit() {
  form.post('/news-person-messages', {
    preserveScroll: true,
    onSuccess: () => {
      notificationStore.setToastNotification('Message successfully sent.', 'info')
      form.reset()
    }
  })
}
</script>
