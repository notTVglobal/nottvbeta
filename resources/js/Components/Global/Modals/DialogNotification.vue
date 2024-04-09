<template>
  <dialog id="dialogNotificationModal" class="modal">
    <div class="modal-box border-2 border-primary text-primary justify-center text-center">
      <h3 class="font-bold text-lg">{{ notificationStore.title }}</h3>
      <p class="py-4">{{ notificationStore.body }}</p>
      <p class="py-4 text-xs text-black">Click or tap anywhere outside to close, or push ESC.</p>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button @click="closeModal">close</button>
    </form>
  </dialog>
</template>

<script setup>
import { useNotificationStore } from "@/Stores/NotificationStore"

const notificationStore = useNotificationStore()

const closeModal = () => {
  const modal = document.getElementById('dialogNotificationModal')
  if (modal && modal.close) {
    modal.close()
    notificationStore.clearNotification()
  }
};

</script>
