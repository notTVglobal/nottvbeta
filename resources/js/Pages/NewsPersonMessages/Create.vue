<template>
  <div>
    <Head title="Create News Person Message"/>

    <div class="place-self-center flex flex-col">
      <div id="topDiv" class="bg-white h-screen text-black dark:bg-gray-900 dark:text-gray-50">
        <NewsHeader>Newsroom</NewsHeader>
        <div class="w-full max-w-7xl px-8 bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
          <div class="py-8 px-4 lg:px-16">
            <h1 class="text-3xl font-bold mb-6">Create News Person Message</h1>
            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <label for="recipient_id" class="block text-lg font-medium mb-2">Recipient</label>
                <NewsPersonSelector v-model="form.recipient_id" :initialRecipient="initialRecipient" />
                <span v-if="form.errors.recipient_id" class="text-red-600">{{ form.errors.recipient_id }}</span>
              </div>
              <div>
                <label for="subject" class="block text-lg font-medium mb-2">Subject</label>
                <input
                    v-model="form.subject"
                    id="subject"
                    type="text"
                    class="w-full p-4 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-black dark:text-gray-50"
                    required
                />
              </div>
              <span v-if="form.errors.subject" class="text-red-600">{{ form.errors.subject }}</span>
              <div>
                <label for="message" class="block text-lg font-medium mb-2">Message</label>
                <textarea
                    v-model="form.message"
                    id="message"
                    class="w-full p-4 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-black dark:text-gray-50"
                    rows="6"
                    required
                ></textarea>
                <span v-if="form.errors.message" class="text-red-600">{{ form.errors.message }}</span>
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
                    @click="cancel"
                    class="w-full py-3 px-6 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition"
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
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import NewsPersonSelector from '@/Components/Pages/NewsPersonMessages/NewsPersonSelector.vue'
import NewsHeader from '@/Components/Pages/News/NewsHeader.vue'
import { computed, onBeforeUnmount, watch } from 'vue'

const newsPersonMessageStore = useNewsPersonMessageStore()
const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()

usePageSetup('newsPersonMessages.create')

let form = useForm({
  recipient_id: newsPersonMessageStore.recipient ? newsPersonMessageStore.recipient.id : '',
  subject: '',
  message: '',
})

const initialRecipient = computed(() => newsPersonMessageStore.recipient)
const initialSubject = computed(() => newsPersonMessageStore.subject)

watch([initialRecipient, initialSubject], ([newRecipient, newSubject]) => {
  if (newRecipient) {
    form.recipient_id = newRecipient.id;
  }
  if (newSubject) {
    form.subject = `${newSubject}`;
  }
}, { immediate: true })

function submit() {
  console.log('Submitting form with values:', form);
  form.post('/news-person-messages', {
    preserveScroll: true,
    onSuccess: () => {
      notificationStore.setToastNotification('Message successfully sent.', 'info')
      form.reset()
    },
    onError: (errors) => {
      console.error('Error submitting form:', errors);
    }
  })
}

function cancel() {
  newsPersonMessageStore.clearRecipient()
  newsPersonMessageStore.clearSubject()
  appSettingStore.btnRedirect('/news-person-messages')
}

onBeforeUnmount(() => {
  newsPersonMessageStore.clearRecipient()
  newsPersonMessageStore.clearSubject()
  newsPersonMessageStore.setSearchInput('')
})
</script>
