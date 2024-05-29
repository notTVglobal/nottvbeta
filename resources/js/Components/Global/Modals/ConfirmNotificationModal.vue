<template>
  <dialog id="confirmNotificationModal" class="modal">
    <div class="modal-box flex items-center border-2 border-primary text-primary justify-center text-center">
      <img src="/storage/images/Ping.png" alt="Ping" class="w-20"/>
      <div class="flex-1 text-center">
        <h3 class="font-bold text-lg">{{ notificationStore.title }}</h3>
        <p class="py-2">{{ notificationStore.body }}</p>
        <div class="flex justify-center mt-4">
          <button @click="confirm" class="btn btn-primary mx-2">Confirm</button>
          <button @click="cancel" class="btn btn-secondary mx-2">Cancel</button>
        </div>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { useNotificationStore } from "@/Stores/NotificationStore"

const notificationStore = useNotificationStore()

const confirm = () => {
  notificationStore.setConfirmationNotification(true)
  closeModal()
}

const cancel = () => {
  notificationStore.setConfirmationNotification(false)
  closeModal()
}

const closeModal = () => {
  const modal = document.getElementById('confirmNotificationModal')
  if (modal && modal.close) {
    modal.close()
  }
}
</script>
