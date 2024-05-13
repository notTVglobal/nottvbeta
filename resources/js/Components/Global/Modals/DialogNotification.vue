<template>
  <dialog id="dialogNotificationModal" class="modal">
    <div class="modal-box flex items-center border-2 border-primary text-primary justify-center text-center">
      <img src="/storage/images/Ping.png" alt="Ping" class="w-20"/>
      <div class="flex-1 text-center">
        <h3 class="font-bold text-lg">{{ notificationStore.title }}</h3>
        <p class="py-2">{{ notificationStore.body }}</p>
      </div>
<!--      <p class="py-4 text-xs text-black bg-white dark:text-white">Click or tap anywhere outside to close, or push ESC.</p>-->
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
