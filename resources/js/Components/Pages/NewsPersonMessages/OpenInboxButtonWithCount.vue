<template>
  <div class="flex items-center">
    <button
        @click="goToInbox"
        :class="buttonClass"
    >
      Inbox ({{ newMessageCount }})
    </button>
  </div>
</template>
<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { onMounted, computed } from 'vue';
import { useNewsPersonMessageStore } from '@/Stores/NewsPersonMessageStore';
import { useNotificationStore } from '@/Stores/NotificationStore';

const newsPersonMessageStore = useNewsPersonMessageStore();
const notificationStore = useNotificationStore();

const page = usePage()

onMounted(() => {
  newsPersonMessageStore.fetchMessageCount();
});

const messageCount = computed(() => newsPersonMessageStore.computedMessageCount);
const newMessageCount = computed(() => newsPersonMessageStore.newMessageCount);

const buttonClass = computed(() => {
  return newMessageCount.value > 0
      ? 'bg-pink-600 hover:bg-pink-700 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded'
      : 'bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded';
});

function goToInbox() {
  router.get('/news-person-messages');
}
</script>