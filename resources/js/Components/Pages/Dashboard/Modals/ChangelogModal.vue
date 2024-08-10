<template>
  <dialog id="changelogModal" class="modal">
    <div class="modal-box p-6 w-full max-w-2xl rounded-lg shadow-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white">
      <h2 class="text-2xl font-extrabold mb-4">What's New in Version {{ changelog.version }}</h2>
      <div class="mb-4">
        <p v-html="changelog.content"></p>
      </div>
      <div class="modal-action flex justify-end">
        <button class="btn bg-blue-700 hover:bg-purple-500 text-white font-bold px-6 py-3 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105" @click="dismiss">
          Got it!
        </button>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore = useNotificationStore()

const props = defineProps({
  changelog: {
    type: Object,
    required: true,
    default: () => ({
      version: '',
      content: '',
    }),
  },
  showChangelog: {
    type: Boolean,
    required: true,
    default: false,
  },
})

const openModal = () => {
  const modal = document.getElementById('changelogModal')
  if (modal) {
    modal.showModal()
  }
}

watch(() => props.showChangelog, (newVal) => {
  if (newVal) {
    openModal()
  }
})

onMounted(() => {
  if (props.showChangelog) {
    openModal()
  }
})

const dismiss = () => {
  axios.post(route('dashboard.dismissChangelog'), {
    version: props.changelog.version
  })
      .then(response => {
        console.log(response.data.message); // Optionally log or use the success message
        document.getElementById('changelogModal').close();
      })
      .catch(error => {
        console.error('Error dismissing changelog:', error);
        notificationStore.setToastNotification(error, 'error')
      });
};

</script>

<style scoped>
/* Additional styling to further enhance the modal */
.modal-box {
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.modal-action button {
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.modal-action button:hover {
  transform: scale(1.05);
}
</style>
